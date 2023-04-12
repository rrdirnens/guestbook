<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\GuestbookMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Event;

class GuestbookController extends Controller
{
    // Display a listing of guestbook messages
    public function index(Request $request,)
    {
        $sortField = $request->input('sort', 'created_at'); // default sort field is created_at
        $sortDirection = $request->input('direction', 'desc'); // default sort direction is desc

        $messages = GuestbookMessage::orderBy($sortField, $sortDirection)
            ->paginate(10);

        return Inertia::render('Guestbook/Index', [
            'messages' => $messages->items(),
        ]);
    }

    // Show the form for creating a new guestbook message
    public function create()
    {
        return Inertia::render('Guestbook/Create');
    }

    // Store a newly created guestbook message in storage
    public function store(Request $request)
    {
        Log::info('store function triggered:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|image|max:4096|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            Log::info('Validation failed:', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $message = new GuestbookMessage();

        $message->name = $request->input('name');
        $message->email = $request->input('email');
        $message->message = $request->input('message');
        $message->ip_address = $request->ip();

        Log::info('Request data:', $request->all());
        Log::info('Validator instance:', $validator->errors()->all());
        Log::info('Message instance:', $message->toArray());

        // The image is optional. If an image is uploaded, store it and set the image_path attribute
        if ($request->hasFile('image')) {
            Log::info('Image uploaded:', ['image' => $request->file('image')]);
            $path = $request->file('image')->store('public/guestbook_images');
            $message->image_path = Storage::url($path);
        }

        $message->save();

        return redirect()->route('guestbook.index')->with('success', 'Message added successfully!');
    }

    // Show the form for editing the specified guestbook message
    public function edit($id)
    {
        $message = GuestbookMessage::findOrFail($id);

        return Inertia::render('Guestbook/Edit', [
            'message' => $message,
        ]);
    }

    // Update the specified guestbook message in storage
    public function update(Request $request, $id)
    {
        Log::info('Update function triggered, request:', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|image|max:4096|mimes:jpg,jpeg,png',
            // 'captcha' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();

            return Inertia::render('Guestbook/Edit', [
                'message' => GuestbookMessage::findOrFail($id),
                'errors' => $errors,
                'input' => $input,
            ])->withViewData(['errors' => $errors, 'input' => $input]);
        }

        $message = GuestbookMessage::findOrFail($id);

        /** 
         * Check if the current user is authorized to update the message. 
         * The user is authorized if: 
         * 1) the IP address of the user who originally added the message matches the IP of the user requesting the change;
         * 2) the message is less than 5 minutes old
         */
        if ($message->ip_address != $request->ip() || $message->created_at->diffInMinutes() > 5) {
            return redirect()->route('guestbook.index')->with('error', 'You are not authorized to update this message!');
        }

        $message->name = $request->input('name');
        $message->email = $request->input('email');
        $message->message = $request->input('message');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/guestbook_images');
            $message->image_path = Storage::url($path);
            $message->image_caption = $request->input('image_caption');
        }
        $message->updated_at = now();

        $message->save();

        return redirect()->route('guestbook.index')->with('success', 'Message updated successfully!');
    }

    // Remove the specified guestbook message from storage
    public function destroy($id)
    {
        $message = GuestbookMessage::findOrFail($id);

        // Check if the current user is authorized to delete the message
        if ($message->ip_address != request()->ip() || $message->created_at->diffInMinutes() > 5) {
            return redirect()->route('guestbook.index')->with('error', 'You are not authorized to delete this message!');
        }

        $message->delete();

        return redirect()->route('guestbook.index')->with('success', 'Message deleted successfully!');
    }
}

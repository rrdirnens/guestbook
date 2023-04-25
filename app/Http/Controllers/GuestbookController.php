<?php

namespace App\Http\Controllers;

use App\Services\GuestbookMessageServiceInterface;
use App\Http\Requests\GuestbookMessageRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class GuestbookController extends Controller
{
    protected $service;

    public function __construct(GuestbookMessageServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the guestbook messages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $messages = $this->service->getAllMessages($sortField, $sortDirection);

        return Inertia::render('Guestbook/Index', [
            'messages' => $messages->items(),
        ])->with('success', 'Showing index!');
    }

    /**
     * Show the form for creating a new guestbook message.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Guestbook/Create');
    }

    /**
     * Store a newly created guestbook message in storage.
     *
     * @param  \App\Http\Requests\GuestbookMessageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GuestbookMessageRequest $request)
    {
        $this->service->storeMessage($request);

        return redirect()->route('guestbook.index')->with('success', 'Message added successfully!');
    }

    /**
     * Show the form for editing the specified guestbook message.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $message = $this->service->getMessageById($id);

        return Inertia::render('Guestbook/Edit', [
            'message' => $message,
        ]);
    }

    /**
     * Update the specified guestbook message in storage.
     *
     * @param  \App\Http\Requests\GuestbookMessageRequest  $request
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function update(GuestbookMessageRequest $request, $id)
    {
        $message = $this->service->getMessageById($id);

        $this->service->updateMessage($message, $request);

        return redirect()->route('guestbook.index')->with('success', 'Message updated successfully!');
    }

    /**
     * Remove the specified guestbook message from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function destroy($id)
    {
        $message = $this->service->getMessageById($id);

        $this->service->deleteMessage($message);

        return to_route('guestbook.index')->with('success', 'Message deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $administrators = Administrator::all();

        return Inertia::render('Admin/Index', [
            'administrators' => $administrators,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:administrators',
            'password' => 'required|min:8|confirmed',
        ]);

        $administrator = new Administrator([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $administrator->save();

        return redirect()->route('admin.index')->with('status', 'Administrator created successfully.');
    }

    public function edit(Administrator $administrator)
    {
        return Inertia::render('Admin/Edit', [
            'administrator' => $administrator,
        ]);
    }

    public function update(Request $request, Administrator $administrator)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:administrators,email,' . $administrator->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $administrator->name = $validated['name'];
        $administrator->email = $validated['email'];

        if (!empty($validated['password'])) {
            $administrator->password = Hash::make($validated['password']);
        }

        $administrator->save();

        return redirect()->route('admin.index')->with('status', 'Administrator updated successfully.');
    }

    public function destroy(Administrator $administrator)
    {
        $administrator->delete();

        return redirect()->route('admin.index')->with('status', 'Administrator deleted successfully.');
    }
}

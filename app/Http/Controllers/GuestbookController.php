<?php


namespace App\Http\Controllers;

use Inertia\Inertia;

class GuestbookController extends Controller
{
    public function index()
    {
        return Inertia::render('Guestbook/Index');
    }
}
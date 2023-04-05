<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::inertia('/', 'Home');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/guestbook', [GuestbookController::class, 'index'])->name('guestbook.index');
Route::get('/guestbook/create', [GuestbookController::class, 'create'])->name('guestbook.create');
Route::post('/guestbook', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::get('/guestbook/{id}/edit', [GuestbookController::class, 'edit'])->name('guestbook.edit');
Route::put('/guestbook/{id}', [GuestbookController::class, 'update'])->name('guestbook.update');
Route::delete('/guestbook/{id}', [GuestbookController::class, 'destroy'])->name('guestbook.destroy');
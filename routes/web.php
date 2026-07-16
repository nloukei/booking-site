<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Models\Venue;

Route::get('/', function () {
    $venues = Venue::all();
    return view('welcome', compact('venues'));
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Venue Details and Availability Routes
Route::get('/venues/{venue}', [\App\Http\Controllers\VenueController::class, 'show'])->name('venues.show');
Route::post('/venues/{venue}/check', [\App\Http\Controllers\VenueController::class, 'checkAvailability'])->name('venues.check');
Route::post('/venues/{venue}/book', [\App\Http\Controllers\VenueController::class, 'book'])->name('venues.book')->middleware('auth');

// Admin venue block route
Route::post('/admin/venues/{venue}/toggle-block', [\App\Http\Controllers\VenueController::class, 'toggleBlock'])->name('admin.venues.toggle-block')->middleware('auth');

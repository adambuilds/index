<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Keep Livewire auth pages available under alternative paths to avoid
    // conflicting with Auth0's SDK-managed /login route.
    Volt::route('local/login', 'auth.login')->name('local.login');
    Volt::route('local/register', 'auth.register')->name('local.register');
    Volt::route('local/forgot-password', 'auth.forgot-password')->name('local.password.request');
    Volt::route('local/reset-password/{token}', 'auth.reset-password')->name('local.password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

// Use Auth0's GET /logout route; disable Livewire's POST /logout to avoid conflicts.
// If you still want a local logout endpoint, expose it under /local/logout.
// Route::post('local/logout', App\Livewire\Actions\Logout::class)->name('local.logout');

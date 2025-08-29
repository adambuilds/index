<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\ThingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\MessageController;

Route::middleware(['auth'])->group(function () {
    Route::resource('things', ThingController::class);
    Route::resource('properties', PropertyController::class);
    Route::resource('relations', RelationController::class);
    Route::resource('messages', MessageController::class);
});

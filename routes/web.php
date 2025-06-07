<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// authenticated routes
Route::middleware('auth')->group(function () {

    Route::resource('subject', SubjectController::class);
    Route::post('subject/{subject}/meta', [SubjectMetaController::class, 'store'])->name('subject.meta.store');
    Route::delete('subject/{subject}/meta/{meta}', [SubjectMetaController::class, 'destroy'])->name('subject.meta.destroy');

    // user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{id}', [SubjectController::class, 'show'])->name('subject.show');
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
    Route::get('subject/search', [SubjectController::class, 'search'])->name('subject.search');
    Route::post('subject/{subject}/relationships', [SubjectRelationshipController::class, 'store'])->name('subject.relationships.store');
    Route::delete('subject/{subject}/relationships/{parent}', [SubjectRelationshipController::class, 'destroy'])->name('subject.relationships.destroy');
    Route::post('subject/{subject}/meta', [SubjectMetaController::class, 'store'])->name('subject.meta.store');
    Route::delete('subject/{subject}/meta/{meta}', [SubjectMetaController::class, 'destroy'])->name('subject.meta.destroy');
    Route::post('subject/{subject}/links', [SubjectLinkController::class, 'store'])->name('subject.links.store');
    Route::delete('subject/{subject}/links/{link}', [SubjectLinkController::class, 'destroy'])->name('subject.links.destroy');
    Route::post('subject/{subject}/tags', [SubjectTagController::class, 'store'])->name('subject.tags.store');
    Route::delete('subject/{subject}/tags/{tag}', [SubjectTagController::class, 'destroy'])->name('subject.tags.destroy');

    // user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{id}', [SubjectController::class, 'show'])->name('subject.show');
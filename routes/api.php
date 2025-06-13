<?php

// dd('API routes loaded');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ClockEventController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/timeclock', [ClockEventController::class, 'store']);

Route::get('/test', function () {
    return response()->json(['message' => 'Test route']);
});

// GET requests
Route::get('/accounts', [JobController::class, 'getAccounts']);
Route::get('/job/{id}', [JobController::class, 'getJob']);
Route::get('/jobs/active', [JobController::class, 'getActiveJobs']);

Route::get('/subjects/search', [SubjectController::class, 'search']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
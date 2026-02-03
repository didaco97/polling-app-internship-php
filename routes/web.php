<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminController;

// Default redirect
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require auth
Route::middleware('auth')->group(function () {
    // Poll routes
    Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/{id}', [PollController::class, 'show'])->name('polls.show');
    
    // Voting routes
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::get('/results/{id}', [VoteController::class, 'results'])->name('vote.results');
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/voters/{id}', [AdminController::class, 'voters'])->name('admin.voters');
        Route::post('/release', [AdminController::class, 'release'])->name('admin.release');
        Route::get('/history/{poll_id}/{ip}', [AdminController::class, 'history'])->name('admin.history');
    });
});

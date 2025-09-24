<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MathController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Landing page
Route::get('/', fn() => view('welcome'));
Route::get('/dashboard', function () {
    return redirect('/math');
});


// Auth routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Protected Visualizer route
Route::middleware('auth')->group(function () {
    Route::get('/math', [MathController::class, 'index'])->name('math');
    Route::post('/math/save', [MathController::class, 'save'])->name('math.save');
    Route::post('/math/clear', [MathController::class, 'clear'])->name('math.clear');
});

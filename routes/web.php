<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MathController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
// Landing page
Route::get('/', fn() => view('welcome'));
Route::get('/dashboard', function () {
    return redirect('/math');
})->name('dashboard');



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
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');

    // Posts
    Route::get('/forum/category/{id}', [PostController::class, 'index'])->name('posts.index');
    // Show "New Post" form
    Route::get('/forum/category/{id}/create', [PostController::class, 'create'])->name('posts.create');

    Route::get('/forum/post/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/forum/post', [PostController::class, 'store'])->name('posts.store');

    // Replies
    Route::post('/forum/post/{id}/reply', [ReplyController::class, 'store'])->name('replies.store');
    Route::post('/forum/post/{post}/replies', [ReplyController::class, 'store'])->name('replies.store')->middleware('auth');

});

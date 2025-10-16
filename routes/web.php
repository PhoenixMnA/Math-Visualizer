<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\MathController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', fn() => view('welcome'));

// Dashboard redirect
Route::get('/dashboard', function () {
    return redirect('/math');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => bcrypt(str()->random(16)),
        ]
    );

    Auth::login($user);

    return redirect('/math');
})->name('google.callback');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Math Visualizer
    Route::get('/math', [MathController::class, 'index'])->name('math');
    Route::post('/math/save', [MathController::class, 'save'])->name('math.save');
    Route::post('/math/clear', [MathController::class, 'clear'])->name('math.clear');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');

    // Posts
    Route::get('/forum/category/{id}', [PostController::class, 'index'])->name('posts.index');
    Route::get('/forum/category/{id}/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/forum/post/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/forum/post', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/forum/post/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Replies
    Route::post('/forum/post/{id}/reply', [ReplyController::class, 'store'])->name('replies.store');
    Route::delete('/forum/reply/{id}', [ReplyController::class, 'destroy'])->name('replies.destroy');
});

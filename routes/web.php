<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostActionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::post('/create-user', [LoginController::class, 'create'])->name('create-user');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/posts/{post}/like', [PostActionController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostActionController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/update', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Redirect old my-posts URL to dashboard
    Route::get('/profile/my-posts', function () {
        return redirect()->route('dashboard', ['tab' => 'myposts']);
    })->name('profile.my-posts');
});

// Public routes (ajax must be before resource to avoid {post} catching "ajax")
Route::get('/posts/ajax', [PostController::class, 'ajaxList'])->name('posts.ajax.list');
Route::resource('posts', PostController::class)->only(['index', 'show', 'store']);

Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

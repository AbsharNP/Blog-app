<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
    
    Route::post('/posts/{post}/like', [PostActionController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostActionController::class, 'comment'])->name('posts.comment');
    
    Route::get('/profile/my-posts', [ProfileController::class, 'myPosts'])->name('profile.my-posts');
});

// Public routes
Route::resource('posts', PostController::class)
    ->only(['index', 'show', 'store']);
Route::get('/posts/ajax', [PostController::class, 'ajaxList'])
    ->name('posts.ajax.list');

Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostActionController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return redirect()->route('posts.index');
})->name('home');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// // Authentication Routes:
// Route::middleware('guest')->group(function () {
//     // Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [LoginController::class, 'login']);
    
//     Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/create-user', [LoginController::class, 'create'])->name('create-user');
    
//     Route::get('/password/reset', function() {
//         return view('auth.forgot-password');
//     })->name('password.request');
// });

// Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');




Route::resource('posts', PostController::class)
    ->only(['index', 'show', 'store']);
Route::get('/posts/ajax', [PostController::class, 'ajaxList'])
    ->name('posts.ajax.list');


Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [PostActionController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostActionController::class, 'comment'])->name('posts.comment');
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtCookieMiddleware;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('sign-up', [AuthController::class, 'signUp'])->name('signUp');
Route::post('sign-up', [AuthController::class, 'signUpPost'])->name('signUp.post');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post'); 

Route::get('/create', [PostController::class, 'create'])->middleware(JwtCookieMiddleware::class)->name('create_post');
Route::post('/create', [PostController::class, 'createPost'])->middleware(JwtCookieMiddleware::class)->name('create_post.post');
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->middleware(JwtCookieMiddleware::class)->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->middleware(JwtCookieMiddleware::class)->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(JwtCookieMiddleware::class)->name('posts.destroy');

// Route::get('/test-category-permission', function () {
//     return 'You have create-category permission!';
// })->middleware('permission:create-category');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('permission:create-category')->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:create-category')->name('categories.store');

Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('permission:update-category')->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('permission:update-category')->name('categories.update');

Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete-category')->name('categories.destroy');


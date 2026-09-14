<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtCookieMiddleware;
use App\Models\Role;

Route::get('/', [PostController::class, 'home'])->name('home');

Route::get('/panel', [DashboardController::class, 'index'])->middleware(JwtCookieMiddleware::class)->name('panel');

Route::get('sign-up', [AuthController::class, 'signUp'])->name('signUp');
Route::post('sign-up', [AuthController::class, 'signUpPost'])->name('signUp.post');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post'); 

Route::get('/create', [PostController::class, 'create'])->middleware('permission:create-post')->name('create_post');
Route::post('/create', [PostController::class, 'createPost'])->middleware('permission:create-post')->name('create_post.post');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/my-posts', [PostController::class, 'myPosts'])->middleware(JwtCookieMiddleware::class)->name('posts.mine');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Route::get('/test-category-permission', function () {
//     return 'You have create-category permission!';
// })->middleware('permission:create-category');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('permission:create-category')->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:create-category')->name('categories.store');

Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('permission:update-category')->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('permission:update-category')->name('categories.update');

Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete-category')->name('categories.destroy');

Route::get('/users', [UserRoleController::class, 'index'])->middleware('permission:assign-role')->name('users.index');
Route::put('/users/{user}/role', [UserRoleController::class, 'update'])->middleware('permission:assign-role')->name('users.role.update');
Route::delete('/users/{user}', [UserRoleController::class, 'destroy'])->middleware('permission:manage-users,assign-role')->name('users.destroy');
Route::patch('/users/{id}/restore', [UserRoleController::class, 'restore'])->middleware('permission:manage-users')->name('users.restore');

Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:create-role,update-role')->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:create-role')->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:create-role')->name('roles.store');

Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:update-role')->name('roles.edit');
Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:update-role')->name('roles.update');

Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete-role')->name('roles.destroy');

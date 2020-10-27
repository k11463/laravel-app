<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\PostScoreController;

Route::get('/users', [UserController::class, 'findUsers']);

Route::post('/user', [UserController::class, 'createUser']);
Route::post('/user/update', [UserController::class, 'updateUser']);
Route::post('/user/check', [UserController::class, 'findUser']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::post('/email', [EmailVerifyController::class, 'sendVerifyCode']);

Route::post('/posts', [PostController::class, 'getAllPosts']);
Route::post('/post/create', [PostController::class, 'createPost']);
Route::post('/post/update', [PostController::class, 'updatePost']);
Route::post('/post/score', [PostScoreController::class, 'index']);
Route::post('/post/{id}', [PostController::class, 'findPostById']);
Route::post('/post/delete/{id}', [PostController::class, 'deletePost']);
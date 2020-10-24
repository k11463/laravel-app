<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailVerifyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Route::middleware('cors')->group(function(){
//     Route::post('/user', function() {
//         return "123";
//     });
//  });

Route::get('/users', [UserController::class, 'findUsers']);

Route::post('/user', [UserController::class, 'createUser']);
Route::post('/user/update', [UserController::class, 'updateUser']);
Route::post('/user/check', [UserController::class, 'findUser']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/email', [EmailVerifyController::class, 'sendVerifyCode']);
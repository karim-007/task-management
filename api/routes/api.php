<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'authentication']);
Route::post('/registration', [\App\Http\Controllers\Auth\LoginController::class, 'registration']);

Route::group(['middleware' => ['history','auth:sanctum']], function () {
    Route::get('/user', [App\Http\Controllers\AuthController::class, 'user']);

    Route::apiResource('task', App\Http\Controllers\TaskController::class);
    Route::apiResource('task-comment', App\Http\Controllers\TaskCommentController::class);

});
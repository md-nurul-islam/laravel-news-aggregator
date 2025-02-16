<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get(uri: '/article', action: [ArticleController::class, 'index']);
    Route::get(uri: '/article/fetch', action: [ArticleController::class, 'fetch']);
    Route::get(uri: '/article/{id}', action: [ArticleController::class, 'details']);
});

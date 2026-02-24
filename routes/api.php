<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'authenticate']);
Route::post('/user', [UserController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);


    Route::controller(ApiTokenController::class)->prefix('api-token')->group(function () {
        Route::post('', 'generate');
    });

    Route::controller(LogController::class)->prefix('log')->group(function () {
        Route::post('', 'store');
    });
});

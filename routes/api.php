<?php

use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    //Public route no token required
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    //Protected route
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [\App\Http\Controllers\Api\UserProfileController::class, 'me']);
        Route::post('profile', [\App\Http\Controllers\Api\UserProfileController::class, 'update']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

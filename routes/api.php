<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

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
        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    });
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('subjects/{id}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::get('rooms', [RoomController::class, 'index'])->name('room.index');
    Route::get('rooms/{id}', [RoomController::class, 'show'])->name('room.show');

    Route::get('shifts', [ShiftController::class, 'index'])->name('shift.index');
    Route::get('shifts/{id}', [ShiftController::class, 'show'])->name('shift.show');

    Route::middleware(['role:' . \App\Constants\RoleConstants::ADMIN])->group(function () {
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::patch('subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

        Route::post('rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::patch('rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

        Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::patch('shifts/{id}', [ShiftController::class, 'update'])->name('shifts.update');
        Route::delete('shifts/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    });
});

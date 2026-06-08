<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AttendanceController;




Route::post('/auth/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function() {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

        Route::get('/company', [CompanyController::class, 'index']);
        
        Route::prefix('attendance')->group(function() {
            Route::post('/check-in', [AttendanceController::class, 'checkIn']);
        });

        Route::prefix('leaves')->group(function() {
            Route::get('/', [\App\Http\Controllers\Api\LeaveController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\LeaveController::class, 'store']);
        });
});

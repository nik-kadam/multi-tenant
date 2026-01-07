<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    
    // User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Modules
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('manage-users', UserController::class); 
});

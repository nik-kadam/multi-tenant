<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/tenant-signup', [AuthController::class, 'tenantSignup']);
Route::post('/tenant-login', [AuthController::class, 'tenantLogin']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/logout', [UserController::class, 'logout']);

    // Employee Routes
    Route::post('/add-employee', [UserController::class, 'addEmployee']);
    Route::get('/get-employee-details/{id}', [UserController::class, 'getEmployeeDetails']);
    Route::post('/check-email', [UserController::class, 'checkEmail']);
    Route::get('/get-employees', [UserController::class, 'getEmployees']);
    Route::post('/update-employee', [UserController::class, 'updateEmployee']);
    Route::post('/delete-employee', [UserController::class, 'deleteEmployee']);

    // Department Routes
    Route::middleware([EnsureUserIsAdmin::class])->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
        Route::post('/add-department', [DepartmentController::class, 'store']);
        Route::get('/get-department-details/{id}', [DepartmentController::class, 'getDetails']);
        Route::post('/update-department', [DepartmentController::class, 'update']);
        Route::post('/delete-department', [DepartmentController::class, 'delete']);
    });

    // Role Routes  
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/add-role', [RoleController::class, 'store']);
    Route::get('/get-role-details/{id}', [RoleController::class, 'getDetails']);
    Route::post('/update-role', [RoleController::class, 'update']);
    Route::post('/delete-role', [RoleController::class, 'delete']);
    Route::post('/add-user', [RoleController::class, 'storeUser']);
    Route::get('/get-user-details/{id}', [RoleController::class, 'getUserDetails']);
    Route::post('/update-user', [RoleController::class, 'updateUser']);
    Route::post('/delete-user', [RoleController::class, 'deleteUser']);
});
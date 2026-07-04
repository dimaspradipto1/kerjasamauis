<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Public Guest Routes
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('loginproses', 'loginproses')->name('loginproses');
    });
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Root redirect
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // User Password Routes
    Route::get('user/{id}/password', [UserController::class, 'updatePasswordForm'])->name('user.updatePasswordForm');
    Route::put('user/{id}/password', [UserController::class, 'updatePassword'])->name('user.updatePassword');

    // User Resource Routes
    Route::resource('user', UserController::class);
});
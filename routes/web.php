<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BentukKegiatanController;
use App\Http\Controllers\SasaranKinerjaController;
use App\Http\Controllers\KriteriaMitraController;
use App\Http\Controllers\SumberDanaController;

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('loginproses', 'loginproses')->name('loginproses');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('user', UserController::class);
    Route::resource('bentuk-kegiatan', BentukKegiatanController::class);
    Route::resource('sasaran-kinerja', SasaranKinerjaController::class);
    Route::resource('kriteria-mitra', KriteriaMitraController::class);
    Route::resource('sumber-dana', SumberDanaController::class);
});
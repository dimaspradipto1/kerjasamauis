<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BentukKegiatanController;
use App\Http\Controllers\SasaranKinerjaController;
use App\Http\Controllers\KriteriaMitraController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\KerjasamaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanKerjasamaController;

Route::redirect('/', '/login');

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
    Route::resource('jenis-dokumen', JenisDokumenController::class);
    Route::resource('mitra', MitraController::class);
    Route::resource('unit-kerja', UnitKerjaController::class);
    
    // Excel Kerjasama Routes
    Route::get('kerjasama/export', [KerjasamaController::class, 'export'])->name('kerjasama.export');
    Route::post('kerjasama/import', [KerjasamaController::class, 'import'])->name('kerjasama.import');
    Route::get('kerjasama/download-template', [KerjasamaController::class, 'downloadTemplate'])->name('kerjasama.download-template');
    Route::resource('kerjasama', KerjasamaController::class);
    
    // AJAX routes for dynamic dynamic forms
    Route::get('kegiatan/ajax/mitra-by-kerjasama/{kerjasamaId}', [KegiatanController::class, 'getMitraByKerjasama']);
    Route::get('kegiatan/ajax/indikator-by-sasaran/{sasaranKinerjaId}', [KegiatanController::class, 'getIndikatorBySasaran']);
    
    // Excel Kegiatan Routes
    Route::get('kegiatan/export', [KegiatanController::class, 'export'])->name('kegiatan.export');
    Route::post('kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import');
    Route::get('kegiatan/download-template', [KegiatanController::class, 'downloadTemplate'])->name('kegiatan.download-template');
    Route::resource('kegiatan', KegiatanController::class);

    // Laporan Kerjasama
    Route::get('laporan-kerjasama', [LaporanKerjasamaController::class, 'index'])->name('laporan.index');
    Route::get('laporan-kerjasama/cetak', [LaporanKerjasamaController::class, 'cetak'])->name('laporan.cetak');
});
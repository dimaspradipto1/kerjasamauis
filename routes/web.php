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
use App\Http\Controllers\RolePermissionController;

Route::redirect('/', '/login');

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('loginproses', 'loginproses')->name('loginproses');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::middleware(['checkpermission:user'])->group(function () {
        Route::resource('user', UserController::class);
    });

    // Role & Permission Management (hanya superadmin)
    Route::get('role-permission/{user}', [RolePermissionController::class, 'index'])->name('role-permission.index');
    Route::put('role-permission/{user}', [RolePermissionController::class, 'update'])->name('role-permission.update');

    // Referensi: Bentuk Kegiatan
    Route::middleware(['checkpermission:bentuk_kegiatan'])->group(function () {
        Route::resource('bentuk-kegiatan', BentukKegiatanController::class);
    });

    // Referensi: Sasaran Kinerja
    Route::middleware(['checkpermission:sasaran_kinerja'])->group(function () {
        Route::resource('sasaran-kinerja', SasaranKinerjaController::class);
    });

    // Referensi: Kriteria Mitra
    Route::middleware(['checkpermission:kriteria_mitra'])->group(function () {
        Route::resource('kriteria-mitra', KriteriaMitraController::class);
    });

    // Referensi: Sumber Dana
    Route::middleware(['checkpermission:sumber_dana'])->group(function () {
        Route::resource('sumber-dana', SumberDanaController::class);
    });

    // Referensi: Jenis Dokumen
    Route::middleware(['checkpermission:jenis_dokumen'])->group(function () {
        Route::resource('jenis-dokumen', JenisDokumenController::class);
    });

    // Data Mitra
    Route::middleware(['checkpermission:mitra'])->group(function () {
        Route::get('mitra/export', [MitraController::class, 'export'])->name('mitra.export');
        Route::resource('mitra', MitraController::class);
    });

    // Unit Kerja
    Route::middleware(['checkpermission:unit_kerja'])->group(function () {
        Route::resource('unit-kerja', UnitKerjaController::class);
    });

    // Data Kerjasama
    Route::middleware(['checkpermission:kerjasama'])->group(function () {
        Route::get('kerjasama/export', [KerjasamaController::class, 'export'])->name('kerjasama.export');
        Route::post('kerjasama/import', [KerjasamaController::class, 'import'])->name('kerjasama.import');
        Route::get('kerjasama/download-template', [KerjasamaController::class, 'downloadTemplate'])->name('kerjasama.download-template');
        Route::resource('kerjasama', KerjasamaController::class);
    });

    // Data Kegiatan
    Route::middleware(['checkpermission:kegiatan'])->group(function () {
        // AJAX routes for dynamic forms
        Route::get('kegiatan/ajax/mitra-by-kerjasama/{kerjasamaId}', [KegiatanController::class, 'getMitraByKerjasama']);
        Route::get('kegiatan/ajax/indikator-by-sasaran/{sasaranKinerjaId}', [KegiatanController::class, 'getIndikatorBySasaran']);

        Route::post('kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import');
        Route::get('kegiatan/download-template', [KegiatanController::class, 'downloadTemplate'])->name('kegiatan.download-template');

        Route::resource('kegiatan', KegiatanController::class);
    });

    // Laporan Kerjasama
    Route::middleware(['checkpermission:laporan'])->group(function () {
        Route::get('laporan-kerjasama', [LaporanKerjasamaController::class, 'index'])->name('laporan.index');
        Route::get('laporan-kerjasama/cetak', [LaporanKerjasamaController::class, 'cetak'])->name('laporan.cetak');
    });
});
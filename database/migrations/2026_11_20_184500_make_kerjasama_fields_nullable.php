<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kerjasamas', function (Blueprint $table) {
            $table->string('nomor_dokumen_kerjasama')->nullable()->change();
            $table->text('deskripsi_kerjasama')->nullable()->change();
            $table->foreignId('sumber_dana_id')->nullable()->change();
            $table->integer('anggaran')->default(0)->nullable()->change();
            $table->date('tanggal_waktu_berlaku')->nullable()->change();
            $table->date('tanggal_akhir_berlaku')->nullable()->change();
            $table->string('status_kerjasama')->default('Aktif')->nullable()->change();
            $table->string('status')->default('active')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tetap nullable untuk fleksibilitas
    }
};

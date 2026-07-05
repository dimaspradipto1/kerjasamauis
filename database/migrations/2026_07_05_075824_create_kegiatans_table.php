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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerjasama_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sasaran_kinerja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bentuk_kegiatan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('indikator_id')->constrained('indikator_sasarans')->cascadeOnDelete();
            $table->string('nomor_dokumen_kegiatan')->nullable();
            $table->string('nomor_dokumen_mitra')->nullable();
            $table->text('judul_kegiatan');
            $table->date('tanggal_awal_kegiatan');
            $table->date('tanggal_akhir_kegiatan');
            $table->text('ruang_lingkup')->nullable();
            $table->text('hasil_pelakasanaan')->nullable();
            $table->integer('nilai_kontrak')->nullable();
            $table->string('link_dokumen_kegiatan')->nullable();
            $table->string('url_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};

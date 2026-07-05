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
        Schema::create('kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen_kerjasama');
            $table->string('nomor_dokumen_mitra')->nullable();
            $table->foreignId('jenis_dokumen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->constrained()->cascadeOnDelete();
            $table->text('judul_kerjasama');
            $table->text('deskripsi_kerjasama');
            $table->foreignId('sumber_dana_id')->constrained()->cascadeOnDelete();
            $table->integer('anggaran');
            $table->date('tanggal_waktu_berlaku');
            $table->date('tanggal_akhir_berlaku');
            $table->string('status_kerjasama');
            $table->string('url_file')->nullable();
            $table->text('hasil_pelaksanaan')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasamas');
    }
};

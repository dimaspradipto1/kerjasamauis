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
        Schema::create('kerjasama_penanggung_jawabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerjasama_pihak_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('nip');
            $table->string('jabatan');
            $table->string('nomor_hp');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasama_penanggung_jawabs');
    }
};

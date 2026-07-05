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
        Schema::create('indikator_sasarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sasaran_kinerja_id')->constrained()->cascadeOnDelete();
            $table->text('indikator_sasaran');
            $table->text('keterangan')->nullable();
            $table->integer('volume')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_sasarans');
    }
};

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
        Schema::create('kerjasama_pihaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerjasama_id')->constrained()->cascadeOnDelete();
            $table->string('pihak_ke');
            $table->string('jenis_pihak');
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasama_pihaks');
    }
};

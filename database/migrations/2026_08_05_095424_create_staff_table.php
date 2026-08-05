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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('nama_staff');
            $table->string('nup')->nullable();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->string('jabatan')->nullable();
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

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
        Schema::table('mitras', function (Blueprint $table) {
            $table->string('negara')->nullable()->default('Indonesia')->after('lingkup_mitra');
            $table->string('provinsi')->nullable()->change();
            $table->string('kabupaten_kota')->nullable()->change();
            $table->string('kecamatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn('negara');
            $table->string('provinsi')->nullable(false)->change();
            $table->string('kabupaten_kota')->nullable(false)->change();
            $table->string('kecamatan')->nullable(false)->change();
        });
    }
};

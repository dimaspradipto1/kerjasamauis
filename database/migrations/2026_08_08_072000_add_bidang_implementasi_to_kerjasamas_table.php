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
            $table->string('bidang_implementasi')->nullable()->after('jenis_dokumen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerjasamas', function (Blueprint $table) {
            $table->dropColumn('bidang_implementasi');
        });
    }
};

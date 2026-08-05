<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add skala_kerjasama & make unit_kerja_id nullable in kerjasamas table
        Schema::table('kerjasamas', function (Blueprint $table) {
            if (!Schema::hasColumn('kerjasamas', 'skala_kerjasama')) {
                $table->json('skala_kerjasama')->nullable()->after('deskripsi_kerjasama');
            }
            $table->foreignId('unit_kerja_id')->nullable()->change();
        });

        // 2. Create pivot table kerjasama_unit_kerja
        if (!Schema::hasTable('kerjasama_unit_kerja')) {
            Schema::create('kerjasama_unit_kerja', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kerjasama_id')->constrained('kerjasamas')->cascadeOnDelete();
                $table->foreignId('unit_kerja_id')->constrained('unit_kerjas')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // 3. Migrate existing unit_kerja_id to pivot table
        $kerjasamas = DB::table('kerjasamas')->whereNotNull('unit_kerja_id')->get();
        foreach ($kerjasamas as $k) {
            $exists = DB::table('kerjasama_unit_kerja')
                ->where('kerjasama_id', $k->id)
                ->where('unit_kerja_id', $k->unit_kerja_id)
                ->exists();
            if (!$exists) {
                DB::table('kerjasama_unit_kerja')->insert([
                    'kerjasama_id' => $k->id,
                    'unit_kerja_id' => $k->unit_kerja_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasama_unit_kerja');

        Schema::table('kerjasamas', function (Blueprint $table) {
            if (Schema::hasColumn('kerjasamas', 'skala_kerjasama')) {
                $table->dropColumn('skala_kerjasama');
            }
        });
    }
};

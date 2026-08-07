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
            $table->text('url_file')->nullable()->change();
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->text('url_file')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerjasamas', function (Blueprint $table) {
            $table->string('url_file')->nullable()->change();
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('url_file')->nullable()->change();
        });
    }
};

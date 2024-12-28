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
        Schema::table('laporanmingguans', function (Blueprint $table) {
            $table->date('start_of_week')->nullable()->after('tahun');
            $table->date('end_of_week')->nullable()->after('start_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporanmingguans', function (Blueprint $table) {
            //
        });
    }
};

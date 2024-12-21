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
        Schema::create('laporanmingguans', function (Blueprint $table) {
            $table->id();
            $table->integer('minggu_ke');
            $table->integer('tahun');
            $table->integer('jumlah_terlambat')->default(0);
            $table->unique(['minggu_ke', 'tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporanmingguans');
    }
};

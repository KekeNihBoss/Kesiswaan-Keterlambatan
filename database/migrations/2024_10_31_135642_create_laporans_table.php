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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->integer('minggu')->nullable()->comment('Nomor minggu keterlambatan');
            $table->integer('jumlah_terlambat')->default(0);
            // $table->date('tanggal')->unique();
            // $table->unsignedInteger('minggu')->after('tanggal')->nullable()->comment('Nomor minggu keterlambatan');
            // $table->integer('jumlah_terlambat')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};

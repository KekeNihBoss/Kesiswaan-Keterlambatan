<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalToLaporanmingguansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporanmingguans', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('minggu_ke'); // Ganti "column_name" dengan kolom sebelumnya
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporanmingguans', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
}

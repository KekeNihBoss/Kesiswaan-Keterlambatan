<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporanmingguan extends Model
{
    use HasFactory;

    protected $fillable = ['minggu_ke', 'tahun', 'jumlah_terlambat'];
}

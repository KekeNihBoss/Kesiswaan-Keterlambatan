<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model // Pastikan menggunakan huruf kecil
{
    use HasFactory;
    // protected $table = 'laporan';
    protected $fillable = ['minggu', 'jumlah_terlambat', 'tanggal']; // Kolom yang bisa diisi
}

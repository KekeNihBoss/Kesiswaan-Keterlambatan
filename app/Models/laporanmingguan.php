<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class laporanmingguan extends Model
{
    use HasFactory;

    protected $table = 'laporanmingguans';
    protected $fillable = ['tanggal', 'minggu_ke', 'tahun', 'jumlah_terlambat'];


public function getStartOfWeekAttribute()
{
    return Carbon::now()
        ->setISODate($this->tahun, $this->minggu_ke)
        ->startOfWeek()
        ->format('Y-m-d');
}

public function getEndOfWeekAttribute()
{
    return Carbon::now()
        ->setISODate($this->tahun, $this->minggu_ke)
        ->endOfWeek()
        ->format('Y-m-d');
}

}

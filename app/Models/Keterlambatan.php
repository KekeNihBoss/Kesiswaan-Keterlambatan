<?php

namespace App\Models;
use App\Models\laporan;

use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    protected $fillable = [

        'siswa_id',
        'tanggal',
        'waktu',
        'alasan',
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }

protected static function booted(){
    static::created(function ($keterlambatan) {
        $tanggal = $keterlambatan->tanggal;
        Laporan::updateOrCreate(
            ['tanggal' => $tanggal],
            ['jumlah_terlambat' => laporan::where('tanggal', $tanggal)->count() + 1]
        );
    });
    static::created(function ($keterlambatan) {
        $siswa = $keterlambatan->siswa;
        $siswa->updateSP();
    });
}
}

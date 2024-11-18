<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'nis',
        'kelas',
        'foto',
        'jumlah_keterlambatan',
    ];
   // app/Models/Siswa.php
public function keterlambatan(){
    return $this->hasMany(Keterlambatan::class);
}
public function updateSP(){
    $countLate = $this->keterlambatan()->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();
    if ($countLate >= 3) {
        $this->update(['sp' => 1]);
    }
}
}

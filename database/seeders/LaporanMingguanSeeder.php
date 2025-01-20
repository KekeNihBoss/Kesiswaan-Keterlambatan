<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\laporanmingguan;
use Carbon\Carbon;

class LaporanMingguanSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama untuk menghindari duplikasi
        LaporanMingguan::truncate();

        // Loop untuk setiap minggu di tahun 2024
        foreach (range(1, 52) as $week) {
            $startOfWeek = Carbon::now()->setISODate(2024, $week)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate(2024, $week)->endOfWeek();

            LaporanMingguan::create([
                'minggu_ke' => $week,
                'tahun' => 2025,
                'jumlah_terlambat' => rand(0, 50), // Data acak, sesuaikan dengan kebutuhan
                'tanggal' => $startOfWeek->format('Y-m-d'),
            ]);

            echo "Minggu ke-$week dari {$startOfWeek->format('Y-m-d')} sampai {$endOfWeek->format('Y-m-d')} selesai.\n";
        }
    }
}

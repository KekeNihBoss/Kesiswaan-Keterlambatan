<?php

namespace App\Filament\Resources\LaporanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Laporan;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class LaporanOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    // protected static ?int $sort = -2;

    protected function getStats(): array {
        $userName = auth()->user()->name ?? 'Guest';

        // Total keterlambatan hari ini
        $jumlahKeterlambatanHariIni = Laporan::whereDate('tanggal', today())->sum('jumlah_terlambat');

        // Data keterlambatan dari Senin hingga Jumat
        $hariPertama = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $jumlahKeterlambatan = [];
        for ($i = 0; $i < 5; $i++) {
            $tanggal = $hariPertama->copy()->addDays($i);
            $jumlahKeterlambatan[] = Laporan::whereDate('tanggal', $tanggal)->sum('jumlah_terlambat');
        }

        // Siswa dalam kategori Danger (keterlambatan 2-3 kali)
        $jumlahDanger = Laporan::whereBetween('jumlah_terlambat', [2, 3])->count();

        return [
            Card::make("Welcome Back!", $userName)
                ->description('Senang melihatmu kembali!')
                ->color('success'),

            Card::make('Kasus Keterlambatan Hari Ini', $jumlahKeterlambatanHariIni)
                ->chart($jumlahKeterlambatan)
                ->description('Total keterlambatan dari Senin hingga Jumat')
                ->color('info'),

            Card::make('Siswa dalam Status Danger', $jumlahDanger)
                ->description('Siswa yang telah terlambat 2-3 kali')
                ->color('danger'),
        ];
    }
}

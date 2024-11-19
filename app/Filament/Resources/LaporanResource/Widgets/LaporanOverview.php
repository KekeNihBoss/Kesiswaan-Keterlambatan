<?php

namespace App\Filament\Resources\LaporanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\laporan;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class LaporanOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    // protected static ?int $sort = -2;

    protected function getStats(): array {
        $userName = auth()->user()->name ?? 'Guest';
        $jumlahKeterlambatanHariIni = Laporan::whereDate('tanggal', today())->sum('jumlah_terlambat');
        $hariPertama = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $jumlahKeterlambatan = [];
        for ($i = 0; $i < 5; $i++) {
            $tanggal = $hariPertama->copy()->addDays($i);
            $jumlahKeterlambatan[] = Laporan::whereDate('tanggal', $tanggal)->sum('jumlah_terlambat');
        }

        return [
            Card::make("Welcome Back!", $userName)
                ->description('Senang melihatmu kembali!')
                ->color('success'),
                // ->extraAttributes(['style' => 'margin-bottom: 20px;']),

            Card::make('Kasus Keterlambatan Hari Ini', $jumlahKeterlambatanHariIni)
                ->chart($jumlahKeterlambatan)
                ->description('Total keterlambatan dari Senin hingga Jumat')
                ->color('info'),
        ];
    }
}

<?php

namespace app\Filament\Resources\LaporanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\laporan;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class LaporanOverview extends BaseWidget {
    // protected static string $view = 'filament.widgets.LaporanOverview';
    protected static bool $isLazy = false;
    protected static ?int $sort = -2;
    protected function getStats(): array {
        $jumlahKeterlambatanHariIni = Laporan::whereDate('tanggal', today())->sum('jumlah_terlambat');
        $hariPertama = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $jumlahKeterlambatan = [];
        for ($i = 0; $i < 5; $i++) {
            $tanggal = $hariPertama->copy()->addDays($i);
            $jumlahKeterlambatan[] = Laporan::whereDate('tanggal', $tanggal)->sum('jumlah_terlambat');
        }

        return [
            Card::make('Kasus Keterlambatan Hari Ini', $jumlahKeterlambatanHariIni)
            ->chart($jumlahKeterlambatan)
            ->description('Total keterlambatan dari Senin hingga Jumat')
            ->color('success'),
            // ->extraAttributes([
            //     'style' => 'width: 100%; margin-bottom: 20px; color: white; background-color: #FBBF24;', // Set full width and spacing
            // ]),
            // ->extraAttributes([
            //     'style' => 'width: 597px; height: auto; .Kasus{background-color: red;};', // Sesuaikan nilai width sesuai kebutuhan
            //     'class' => 'Kasus'
            // ]),

            // Card::make('Menu Kasus Keterlambatan', '---')
            //     ->description('Button Redirect Ke Menu Keterlambatan')
            //     ->color('danger'),
                // ->extraAttributes([
                //     'style' => 'background-color: #F87171; color: white;',
                // ]),

            // Card::make('Template Word Pemanggilan Siswa', '---')
            //     ->description('Button Redirect Ke Menu Template')
            //     ->color('success'),
            //     // ->extraAttributes([
            //     //     'class' => 'cursor-pointer',
            //     //     'style' => 'background-color: #34D399; color: white;',
            //     // ]),
        ];
    }
}

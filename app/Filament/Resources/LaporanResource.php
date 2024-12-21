<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Widgets\LaporanOverview;
use App\Filament\Resources\LaporanResource\Pages;
use App\Models\laporan;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class LaporanResource extends Resource
{
    protected static ?string $model = laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan harian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Keterlambatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_terlambat')
                    ->label('Jumlah Siswa Terlambat'),
            ])
            ->filters([
                // Filter Berdasarkan Bulan
                SelectFilter::make('bulan')
                    ->label('Pilih Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->query(function ($query, $data) {
                        if ($data) {
                            $query->whereMonth('tanggal', $data);
                        }
                    }),
    
                // Filter Berdasarkan Rentang Tanggal
                Filter::make('tanggal_range')
                    ->label('Cari Berdasarkan Rentang Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['start_date'] && $data['end_date']) {
                            $query->whereBetween('tanggal', [
                                Carbon::parse($data['start_date']),
                                Carbon::parse($data['end_date']),
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('view')
                    ->label('View Detail')
                    ->color('success')
                    ->modalHeading('Detail Keterlambatan')
                    ->modalContent(function ($record) {
                        $keterlambatan = \App\Models\Keterlambatan::where('tanggal', $record->tanggal)
                            ->with('siswa')
                            ->get();
                        return view('filament.resources.laporan.view-keterlambatan', [
                            'tanggal' => $record->tanggal,
                            'keterlambatan' => $keterlambatan,
                        ]);
                    }),
            ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            'detail' => Pages\DetailLaporan::route('/{tanggal}'),
            // 'create' => Pages\CreateLaporan::route('/create'),
            // 'edit' => Pages\EditLaporan::route('/{record}/edit'),
        ];
    }

    public static function simpanRekapHarian(){
        $tanggalMingguIni = Carbon::now()->startOfWeek();
        $tanggalAkhirMingguIni = Carbon::now()->endOfWeek();
        $keterlambatanData = Keterlambatan::whereBetween('tanggal', [
            $tanggalMingguIni,
            $tanggalAkhirMingguIni,
        ])
        ->select('tanggal')
        ->selectRaw('COUNT(DISTINCT siswa_id) as jumlah_terlambat')
        ->groupBy('tanggal')
        ->get();

        foreach ($keterlambatanData as $data) {
            $laporan = laporan::where('tanggal', $data->tanggal)->first();
            if ($laporan) {
                $laporan->update(['jumlah_terlambat' => $data->jumlah_terlambat]);
            } else {
                laporan::create([
                    'jumlah_terlambat' => $data->jumlah_terlambat,
                    'tanggal' => $data->tanggal,
                ]);
            }
        }
    }

    public static function getWidgets(): array {
        return [
            LaporanResource\Widgets\LaporanOverview::class,
        ];
    }

    public static function getNavigationGroup(): ?string {
        return 'Laporan';
    }

    protected function mutateFormDataBeforeFill(array $data): array{
    $data['user_id'] = auth()->id();
    return $data;
    }
}

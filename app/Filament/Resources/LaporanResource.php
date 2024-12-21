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
                //
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

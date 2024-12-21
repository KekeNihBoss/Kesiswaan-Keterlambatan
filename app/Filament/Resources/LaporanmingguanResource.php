<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanmingguanResource\Pages;
use App\Filament\Resources\LaporanmingguanResource\RelationManagers;
use App\Models\Laporanmingguan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class LaporanmingguanResource extends Resource
{
    protected static ?string $model = Laporanmingguan::class;


    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan mingguan';
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
            Tables\Columns\TextColumn::make('minggu_ke')->label('Minggu Ke'),
            Tables\Columns\TextColumn::make('tahun')->label('Tahun'),
            Tables\Columns\TextColumn::make('jumlah_terlambat')->label('Jumlah Terlambat'),
        ])
        ->defaultSort('tahun', 'desc')
        ->defaultSort('minggu_ke', 'desc')


        ->filters([
            //
        ])
        ->actions([
    Tables\Actions\ViewAction::make('view')
        ->label('View Detail')
        ->color('success')
        ->modalHeading('Detail Keterlambatan Mingguan')
        ->modalContent(function ($record) {
            // Hitung rentang tanggal berdasarkan minggu ke dan tahun
            $startOfWeek = Carbon::now()
                ->setISODate($record->tahun, $record->minggu_ke)
                ->startOfWeek();
            $endOfWeek = Carbon::now()
                ->setISODate($record->tahun, $record->minggu_ke)
                ->endOfWeek();

            $keterlambatan = \App\Models\Keterlambatan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                ->with('siswa')
                ->get();

            return view('filament.resources.laporan-mingguan.view-keterlambatan', [
                'minggu_ke' => $record->minggu_ke,
                'tahun' => $record->tahun,
                'keterlambatan' => $keterlambatan,
                'startOfWeek' => $startOfWeek->format('Y-m-d'),
                'endOfWeek' => $endOfWeek->format('Y-m-d'),
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


    public static function getNavigationGroup(): ?string {
        return 'Laporan';
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanmingguans::route('/'),
            'create' => Pages\CreateLaporanmingguan::route('/create'),
            'edit' => Pages\EditLaporanmingguan::route('/{record}/edit'),
        ];
    }
}

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

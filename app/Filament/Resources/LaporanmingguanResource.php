<?php

namespace App\Filament\Resources;

use App\Exports\LaporanExport;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\DatePicker;


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
            Tables\Columns\TextColumn::make('start_of_week')
                ->label('Tanggal Awal Minggu')
                ->sortable(),
            Tables\Columns\TextColumn::make('end_of_week')
                ->label('Tanggal Akhir Minggu')
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\Filter::make('minggu_ke')
            ->form([
                Forms\Components\TextInput::make('minggu_ke')
                    ->label('Minggu Ke')
                    ->numeric(),
            ])
            ->query(function (Builder $query, array $data) {
                return $query->when($data['minggu_ke'], fn($q) => $q->where('minggu_ke', $data['minggu_ke']));
            }),

    Tables\Filters\Filter::make('created_at')
    ->form([
        DatePicker::make('created_from'),
        DatePicker::make('created_until'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    })
])
    
    ->defaultSort('tahun', 'desc')
    ->defaultSort('minggu_ke', 'desc')
    

        ->actions([
            Tables\Actions\ViewAction::make('view')
            ->modalContent(function ($record) {
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
        

        
        Tables\Actions\ButtonAction::make('Export')
                    ->label('Export to Excel')
                    ->icon('heroicon-s-arrow-down-tray') // Ikon dari Heroicons
                    ->color('success')
                    ->action(fn () => Excel::download(new LaporanExport, 'laporan.xlsx')),
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

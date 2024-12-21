<?php

namespace App\Filament\Resources\LaporanmingguanResource\Pages;

use App\Filament\Resources\LaporanmingguanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanmingguans extends ListRecords
{
    protected static string $resource = LaporanmingguanResource::class;
    protected static ?string $title = 'Laporan Mingguan';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}

<?php

// ListLaporans.php
namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        LaporanResource::simpanRekapMingguan();
    }
    protected function canCreate(): bool{
    return false;
    }
}

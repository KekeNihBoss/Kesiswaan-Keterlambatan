<?php

namespace App\Filament\Resources\LaporanmingguanResource\Pages;

use App\Filament\Resources\LaporanmingguanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanmingguan extends EditRecord
{
    protected static string $resource = LaporanmingguanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

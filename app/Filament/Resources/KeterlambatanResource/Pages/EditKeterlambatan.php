<?php

namespace App\Filament\Resources\KeterlambatanResource\Pages;

use App\Filament\Resources\KeterlambatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeterlambatan extends EditRecord
{
    protected static string $resource = KeterlambatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}

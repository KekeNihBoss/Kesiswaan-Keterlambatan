<?php

namespace App\Filament\Resources\KeterlambatanResource\Pages;

use App\Filament\Resources\KeterlambatanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKeterlambatan extends CreateRecord
{
    protected static string $resource = KeterlambatanResource::class;
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}

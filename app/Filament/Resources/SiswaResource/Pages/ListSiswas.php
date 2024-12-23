<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Log;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;
    protected static ?string $title = 'Daftar Siswa';

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make()
                ->label('Siswa Baru'),
            Actions\Action::make('importSiswa')
                ->label('Impor Data Siswa')
                ->requiresConfirmation()
                ->modalHeading('Impor Data Siswa')
                ->modalDescription('`')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->directory('uploads')
                        ->preserveFilenames(),
                ])
                ->action(function (array $data) {
                    if (empty($data['file'])) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('File tidak ditemukan!')
                            ->danger()
                            ->send();
                        return;
                    } try {
                        $filePath = Storage::disk('public')->path('uploads/' . basename($data['file']));
                        Excel::import(new SiswaImport, $filePath);
                        Notification::make()
                            ->title('Sukses')
                            ->body('Data siswa berhasil diimpor!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Log::error('Gagal mengimpor data: ' . $e->getMessage());
                        Notification::make()
                            ->title('Gagal')
                            ->body('Gagal mengimpor data siswa: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            ];
    }
}

<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Imports\SiswaImport;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportSiswa extends Page
{
    protected static string $resource = SiswaResource::class;
    protected static string $view = 'filament.resources.siswa-resource.pages.import-siswa';

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($request->hasFile('file')) {
            Log::info('File uploaded: ' . $request->file('file')->getClientOriginalName());

            try {
                Excel::import(new SiswaImport, $request->file('file'));
                $this->notify('success', 'Data siswa berhasil diimpor!');
            } catch (\Exception $e) {
                Log::error('Import error: ' . $e->getMessage());
                $this->notify('danger', 'Gagal mengimpor data: ' . $e->getMessage());
            }
        } else {
            Log::warning('No file uploaded.');
            $this->notify('danger', 'Tidak ada file yang diunggah.');
        }
    }

    protected function getActions(): array
    {
        return [
            Action::make('Import Siswa')
                ->label('Unggah File Excel')
                ->action('importData')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']),
                ]),
        ];
    }
}

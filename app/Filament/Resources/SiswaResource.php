<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use App\Models\Keterlambatan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar Siswa';

    public static function getTitle(): string{
        return 'Data Siswa';
    }


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama'),
                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('kelas')
                    ->label('Kelas')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->color(function ($record) {
                        $keterlambatanCount = $record->keterlambatan()->count();
                        if ($record->sp == 0) {
                            return 'default';
                        } elseif ($keterlambatanCount == 2) {
                            return 'warning';
                        } elseif ($keterlambatanCount >= 3) {
                            return 'danger';
                        }
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('sp')
                    ->sortable()
                    ->label('SP')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('importSiswa')
                    ->label('Impor Data Siswa')
                    ->requiresConfirmation()
                    ->modalHeading('Impor Data Siswa')
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
                        }

                        try {
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
            ])
            ->actions([
                Action::make('updateStatus')
                ->label('Sudah Dipanggil?')
                ->action(function ($record) {
                    // Reset status SP ke 0
                    $record->update(['sp' => 0]);

                    // Kirim notifikasi
                    Notification::make()
                        ->title('Status Diperbarui')
                        ->body('Nama siswa telah dipulihkan ke status normal.')
                        ->success()
                        ->send();
                })
                ->visible(function ($record) {
                    // Tombol hanya terlihat jika SP lebih besar atau sama dengan 1
                    return $record->sp >= 1;
                })
                ->requiresConfirmation()
                ->color('success'),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('inputKeterlambatan')
                    ->label('Input Keterlambatan')
                    ->form([
                        Forms\Components\Hidden::make('siswa_id')->default(fn ($record) => $record->id), // Add this line
                        Forms\Components\TextInput::make('tanggal')
                            ->label('Tanggal Keterlambatan')
                            ->default(Carbon::now()->format('Y-m-d'))
                            ->required(),
                            Forms\Components\TextInput::make('waktu')
                            ->label('Waktu Keterlambatan')
                            ->default(now()->format('H:i'))  // Menggunakan waktu sekarang (format 24 jam)
                            ->required()
                            ->rules(['date_format:H:i'])  // Validasi untuk memastikan format waktu 24 jam
                            ->helperText('Format: HH:mm (Contoh: 14:30)')
                            ->extraAttributes([
                                'inputmode' => 'numeric', // Menampilkan keyboard angka di perangkat mobile
                                'pattern' => '[0-9]*', // Validasi untuk memastikan hanya angka
                            ]),
                        Forms\Components\Textarea::make('alasan')
                            ->label('Alasan')
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        if (empty($data['siswa_id'])) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('ID siswa tidak ditemukan!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $fullDateTime = Carbon::parse($data['tanggal'] . ' ' . $data['waktu']); //menggabungkan waktu dan tanggal
                        Keterlambatan::create([
                            'siswa_id' => $data['siswa_id'],
                            'tanggal' => $data['tanggal'],
                            'waktu' => $fullDateTime, //menyimpan sebagai datetime
                            'alasan' => $data['alasan'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data keterlambatan berhasil disimpan!')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([]); // Jika ada filter, tambahkan di sini
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return 'Data Keterlambatan';
    }
}

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
use Illuminate\Support\Facades\DB;


class SiswaResource extends Resource {
    protected static ?string $model = Siswa::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar Siswa';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Siswa';

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

    public static function table(Table $table): Table{
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('nama')
            ->label('Nama')
            ->searchable()
            ->color(function ($record) {
                $count = $record->keterlambatan()->count();
                if ($count == 2) {
                    return 'warning';
                } elseif ($count >= 3) {
                    return 'danger';
                }
                return 'default';
            })
            ->sortable(),

        Tables\Columns\TextColumn::make('nis')
            ->label('NIS')
            ->searchable(),

            Tables\Columns\TextColumn::make('kelas')
            ->label('Kelas')
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('jumlah_keterlambatan')
            ->label('Jumlah Keterlambatan')
            ->getStateUsing(function ($record) {
                return $record->keterlambatan()->count();
            })
            ->sortable()

            ])
            ->actions([
                Action::make('updateStatus')
                ->label('Sudah Dipanggil?')
                ->action(function ($record) {
                    if ($record->keterlambatan()->count() >= 3) {
                        $record->keterlambatan()->delete();

                        Notification::make()
                            ->title('Status Diperbarui')
                            ->body('Nama siswa telah dipulihkan ke status normal.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Siswa belum mencapai 3 keterlambatan.')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(function ($record) {
                    return $record->keterlambatan()->count() >= 3;
                })
                ->requiresConfirmation()
                ->color('success'),

                Tables\Actions\Action::make('inputKeterlambatan')
                    ->label('Input Keterlambatan')
                    ->form([
                        Forms\Components\Hidden::make('siswa_id')->default(fn ($record) => $record->id),
                        Forms\Components\TextInput::make('tanggal')
                            ->label('Tanggal Keterlambatan')
                            ->readonly()
                            ->default(Carbon::now()->format('Y-m-d'))
                            ->required(),
                        Forms\Components\TextInput::make('waktu')
                            ->label('Waktu Keterlambatan')
                            ->default(now()->format('H:i'))
                            ->required()
                            ->rules(['date_format:H:i'])
                            ->helperText('Format: HH:mm (Contoh: 14:30)')
                            ->readonly()
                            ->extraAttributes([
                                'inputmode' => 'numeric',
                                'pattern' => '[0-9]*',
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

                        $fullDateTime = Carbon::parse($data['tanggal'] . ' ' . $data['waktu']);
                        Keterlambatan::create([
                            'siswa_id' => $data['siswa_id'],
                            'tanggal' => $data['tanggal'],
                            'waktu' => $fullDateTime,
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
                Tables\Actions\BulkAction::make('inputKeterlambatan')
                    ->label('Input Keterlambatan')
                    ->form([
                        Forms\Components\Hidden::make('siswa_id'),
                        Forms\Components\TextInput::make('tanggal')
                            ->label('Tanggal Keterlambatan')
                            ->readonly()
                            ->default(Carbon::now()->format('Y-m-d'))
                            ->required(),
                        Forms\Components\TextInput::make('waktu')
                            ->label('Waktu Keterlambatan')
                            ->default(now()->format('H:i:s'))
                            ->required()
                            ->rules(['date_format:H:i:s'])
                            ->helperText('Format: HH:mm:ss (Contoh: 14:30.30)')
                            ->readonly()
                            ->extraAttributes([
                                'inputmode' => 'numeric',
                                'pattern' => '[0-9]*',
                            ]),
                        Forms\Components\Textarea::make('alasan')
                            ->label('Alasan')
                            ->nullable(),
                    ])
                    ->action(function (array $data, $records) {
                        foreach ($records as $record) {
                            DB::table('keterlambatans')->insert([
                                'siswa_id' => $record->id,
                                'tanggal' => $data['tanggal'],
                                'waktu' => $data['waktu'],
                                'alasan' => $data['alasan'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }),
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Hapus Terpilih'),
            ])
            ->filters([]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'create' => Pages\CreateSiswa::route('/create'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return 'Data Keterlambatan';
    }
}

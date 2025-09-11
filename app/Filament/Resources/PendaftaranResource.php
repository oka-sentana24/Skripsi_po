<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendaftaranResource extends Resource
{
    // Model yang digunakan untuk resource ini
    protected static ?string $model = Pendaftaran::class;

    // Icon di sidebar
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Label di menu sidebar (jamak)
    protected static ?string $pluralModelLabel = 'Registrasi Antrean';

    // Label yang muncul di menu sidebar
    protected static ?string $navigationLabel = 'Registrasi Antrean';

    // Urutan tampil di sidebar (semakin kecil semakin atas)
    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'eksekutif']);
    }


    /**
     * Form input untuk create / edit
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Registrasi')
                    ->description('Lengkapi data registrasi pasien')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pasien_id')
                            ->label('Pasien')
                            ->searchable()
                            ->options(
                                \App\Models\Pasien::limit(50)->get()->mapWithKeys(
                                    fn($pasien) => [$pasien->id => "{$pasien->no_rm} - {$pasien->nama}"]
                                )
                            )
                            ->getSearchResultsUsing(
                                fn(string $query) =>
                                \App\Models\Pasien::query()
                                    ->where(function ($q) use ($query) {
                                        $q->where('nama', 'like', "%{$query}%")
                                            ->orWhere('no_rm', 'like', "%{$query}%");
                                    })
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn($pasien) => [$pasien->id => "{$pasien->no_rm} - {$pasien->nama}"])
                            )
                            ->getOptionLabelUsing(
                                fn($value): ?string =>
                                optional(\App\Models\Pasien::find($value))
                                    ?->no_rm . ' - ' . optional(\App\Models\Pasien::find($value))?->nama
                            )
                            ->required(),


                        // Tanggal registrasi
                        DatePicker::make('tanggal_pendaftaran')
                            ->label('Tanggal Registrasi')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->toDateString())
                            ->native(false) // supaya pakai datepicker modern, bukan bawaan browser

                            ->suffixIcon('heroicon-o-calendar'), // ikon di sebelah kanan input

                        // Catatan tambahan
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan / Riwayat')
                            ->rows(3),
                    ]),
            ]);
    }

    /**
     * Tabel list data registrasi antrean
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nomor antrean
                Tables\Columns\TextColumn::make('antrean.nomor_antrean')
                    ->label('Nomor Antrean')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pasien.no_rm')
                    ->label('Pasien')
                    ->sortable()
                    ->searchable(),

                // Nama pasien
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Pasien')
                    ->sortable()
                    ->searchable(),

                // Tanggal registrasi
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Registrasi')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                // Catatan / riwayat
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan / Riwayat')
                    ->limit(50)
                    ->searchable(),

                // Status (misal: menunggu, selesai, batal)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                // Filter berdasarkan tanggal registrasi
                Filter::make('tanggal_pendaftaran')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Tanggal Registrasi')
                            ->default(now()),
                    ])
                    ->query(
                        fn(Builder $query, array $data): Builder =>
                        $query->when(
                            $data['tanggal'] ?? null,
                            fn($q, $date) => $q->whereDate('tanggal_pendaftaran', $date)
                        )
                    )
                    ->default(), // aktif secara default
            ])
            ->actions([
                // Aksi edit per baris
                Tables\Actions\EditAction::make()->label(''),

                // Aksi cetak kartu pasien
                Tables\Actions\Action::make('print_card')
                    ->label('')
                    ->icon('heroicon-o-printer')
                    ->tooltip('Cetak Kartu Pasien')
                    ->modalHeading('Kartu Pasien')
                    ->modalWidth('lg')
                    ->modalContent(
                        fn($record) =>
                        view('livewire.nomor-antrean', ['pasien' => $record])
                    )
                    ->modalActions([
                        Action::make('close')
                            ->label('Tutup')
                            ->color('gray')
                            ->close(),

                        Action::make('print')
                            ->label('Cetak Kartu')
                            ->button()
                            ->color('primary')
                            ->extraAttributes(['onclick' => 'window.print()']),
                    ]),
            ])
            ->bulkActions([
                // Aksi massal
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
                ]),
            ]);
    }

    // Relasi ke resource lain (kalau ada)
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Routing halaman CRUD
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit'   => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}

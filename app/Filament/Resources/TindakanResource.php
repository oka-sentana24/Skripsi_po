<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TindakanResource\Pages;
use App\Filament\Resources\TindakanResource\RelationManagers;
use App\Models\Pendaftaran;
use App\Models\Terapis;
use App\Models\Tindakan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TindakanResource extends Resource
{
    protected static ?string $model = Tindakan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    // protected static ?string $navigationGroup = 'Manajemen Tindakan';
    // protected static ?string $modelLabel = 'Tindakan';
    protected static ?string $pluralModelLabel = 'Pemeriksaan Pasien';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['terapis']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Section::make('Data Pasien')
                //     ->description('Informasi pasien dari nomor antrean yang dipilih')
                //     ->columns(2)
                //     ->schema([
                //         Forms\Components\Select::make('pendaftaran_id')
                //             ->label('Nomor Antrean')
                //             ->options(
                //                 Pendaftaran::with('antrean')
                //                     ->whereHas('antrean')
                //                     ->get()
                //                     ->pluck('antrean.nomor_antrean', 'id')
                //             )
                //             ->searchable()
                //             ->required()
                //             ->disabled()
                //             ->reactive(),

                //         Forms\Components\Select::make('pendaftaran_id')
                //             ->label('Nama Pasien')
                //             ->options(
                //                 Pendaftaran::with('pasien')
                //                     ->whereHas('antrean') // pastikan hanya yang punya antrean
                //                     ->get()
                //                     ->mapWithKeys(function ($pendaftaran) {
                //                         return [
                //                             $pendaftaran->id => $pendaftaran->pasien?->nama ?? '-', // tampilkan nama pasien
                //                         ];
                //                     })
                //             )
                //             ->searchable()
                //             ->disabled()
                //             ->required()
                //             ->reactive(),

                //         Forms\Components\Textarea::make('riwayat_pasien')
                //             ->label('Riwayat Pasien')
                //             ->disabled()
                //             ->rows(3)
                //             ->dehydrated(false)
                //             ->reactive()
                //             ->afterStateHydrated(function ($component, $state, $get) {
                //                 $pendaftaranId = $get('pendaftaran_id');
                //                 if ($pendaftaranId) {
                //                     $riwayat = Pendaftaran::find($pendaftaranId)?->catatan;
                //                     $component->state($riwayat ?? '-');
                //                 }
                //             }),
                //     ]),

                Forms\Components\Section::make('Informasi Pasien')
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('No Rekam Medik')
                            ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.no_rm', 'id'))
                            ->searchable()
                            ->disabled()
                            ->required(),
                        Select::make('pendaftaran_id')
                            ->label('Nama Pasien')
                            ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.nama', 'id'))
                            ->searchable()
                            ->disabled()
                            ->required(),
                        Select::make('pendaftaran_id')
                            ->label('Tanggal Lahir')
                            ->options(
                                Pendaftaran::with('pasien')->get()->mapWithKeys(function ($pendaftaran) {
                                    $tanggal = $pendaftaran->pasien?->tanggal_lahir
                                        ? Carbon::parse($pendaftaran->pasien->tanggal_lahir)->format('d/M/Y')
                                        : '-';
                                    return [$pendaftaran->id => $tanggal];
                                })
                            )
                            ->searchable()
                            ->disabled()
                            ->required(),
                        Select::make('pendaftaran_id')
                            ->label('No HP')
                            ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.no_hp', 'id'))
                            ->searchable()
                            ->disabled()
                            ->required(),
                        Select::make('pendaftaran_id')
                            ->label('Alamat')
                            ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.alamat', 'id'))
                            ->searchable()
                            ->disabled()
                            ->required(),
                        Select::make('pendaftaran_id')
                            ->label('Email')
                            ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.email', 'id'))
                            ->searchable()
                            ->disabled()
                            ->required(),

                        Forms\Components\Textarea::make('riwayat_pasien')
                            ->label('Riwayat Pasien')
                            ->disabled()
                            ->rows(3)
                            ->dehydrated(false)
                            ->reactive()
                            ->afterStateHydrated(function ($component, $state, $get) {
                                $pendaftaranId = $get('pendaftaran_id');
                                if ($pendaftaranId) {
                                    $riwayat = Pendaftaran::find($pendaftaranId)?->catatan;
                                    $component->state($riwayat ?? '-');
                                }
                            }),


                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pemeriksaan')
                    ->columns(2)
                    ->description('Detail pemeriksaan pasien')
                    ->schema([
                        Forms\Components\Select::make('terapis_id')
                            ->label('Terapis')
                            ->options(Terapis::all()->pluck('nama', 'id')) // ambil list terapi
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('layanans')
                            ->label('Jenis Layanan')
                            ->relationship('layanans', 'nama') // relasi ke model Layanan
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Pemeriksaan')
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.antrean.nomor_antrean')
                    ->label('Nomor Antrean')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pendaftaran.pasien.no_rm')
                    ->label('Nomor Rekam Medik')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pendaftaran.pasien.nama')
                    ->label('Nama Pasien')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('terapis.nama')
                    ->label('Terapis')
                    ->sortable()
                    ->searchable()
                    ->visible(fn($record) => $record?->status === 'selesai'),

                TagsColumn::make('layanans')
                    ->label('Jenis Layanan')
                    ->getStateUsing(fn($record) => $record->layanans->pluck('nama')->toArray())
                    ->visible(fn($record) => $record?->status === 'selesai'),

                Tables\Columns\TextColumn::make('pendaftaran.catatan')
                    ->label('Riwayat')
                    ->limit(30)
                    ->wrap()
                    ->visible(fn($record) => $record?->status === 'selesai'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->wrap()
                    ->visible(fn($record) => $record?->status === 'selesai'),

                // Status
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'info'    => 'proses',
                        'success' => 'selesai',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['tanggal'] ?? null, function (Builder $query, $tanggal) {
                            $query->whereDate('created_at', $tanggal);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTindakans::route('/'),
            // 'create' => Pages\CreateTindakan::route('/create'),
            'edit' => Pages\EditTindakan::route('/{record}/edit'),
        ];
    }
}

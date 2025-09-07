<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TindakanResource\Pages;
use App\Filament\Resources\TindakanResource\RelationManagers;
use App\Models\Pendaftaran;
use App\Models\Terapis;
use App\Models\Tindakan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pasien')
                    ->description('Informasi pasien dari nomor antrean yang dipilih')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pendaftaran_id')
                            ->label('Nomor Antrean')
                            ->options(
                                Pendaftaran::with('antrean')
                                    ->whereHas('antrean')
                                    ->get()
                                    ->pluck('antrean.nomor_antrean', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->disabled()
                            ->reactive(),

                        Forms\Components\Select::make('pendaftaran_id')
                            ->label('Nama Pasien')
                            ->options(
                                Pendaftaran::with('pasien')
                                    ->whereHas('antrean') // pastikan hanya yang punya antrean
                                    ->get()
                                    ->mapWithKeys(function ($pendaftaran) {
                                        return [
                                            $pendaftaran->id => $pendaftaran->pasien?->nama ?? '-', // tampilkan nama pasien
                                        ];
                                    })
                            )
                            ->searchable()
                            ->disabled()
                            ->required()
                            ->reactive(),

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
                    ]),

                Forms\Components\Section::make('Pemeriksaan')
                    ->columns(2)
                    ->description('Detail pemeriksaan pasien')
                    ->schema([
                        Forms\Components\Select::make('terapi_id')
                            ->label('Terapi')
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

                Tables\Columns\TextColumn::make('pendaftaran.pasien.nama')
                    ->label('Nama Pasien')
                    ->sortable()
                    ->searchable(),



                Tables\Columns\TextColumn::make('terapis.nama')
                    ->label('Terapis')
                    ->sortable()
                    ->searchable(),

                TagsColumn::make('layanans')
                    ->label('Jenis Layanan')
                    ->getStateUsing(fn($record) => $record->layanans->pluck('nama')->toArray()),

                Tables\Columns\TextColumn::make('pendaftaran.catatan')
                    ->label('Riwayat')
                    ->limit(30)
                    ->wrap(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
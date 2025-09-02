<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TindakanResource\Pages;
use App\Filament\Resources\TindakanResource\RelationManagers;
use App\Models\Pendaftaran;
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
                Forms\Components\Select::make('pendaftaran_id')
                    ->label('Nomor Antrean')
                    ->options(
                        Pendaftaran::with('antrean')
                            ->get()
                            ->pluck('antrean.nomor_antrean', 'id')
                    )
                    ->searchable()
                    ->disabled()
                    ->required(),

                Forms\Components\Select::make('pendaftaran_id')
                    ->label('Pasien')
                    ->options(
                        Pendaftaran::with('pasien')
                            ->get()
                            ->pluck('pasien.nama', 'id')
                    )
                    ->searchable()
                    ->disabled()
                    ->required(),

                Forms\Components\Select::make('terapis_id')
                    ->label('Terapis')
                    ->relationship('terapis', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('layanans')
                    ->label('Jenis Layanan')
                    ->relationship('layanans', 'nama') // harus sama persis dengan relasi di model
                    ->multiple()
                    ->required(),


                Forms\Components\Textarea::make('catatan')
                    ->label('Riwayat pasien')
                    ->nullable()
                    ->default(function ($get) {
                        $pendaftaranId = $get('pendaftaran_id');
                        if ($pendaftaranId) {
                            $pendaftaran = Pendaftaran::find($pendaftaranId);
                            return $pendaftaran?->catatan; // ambil catatan dari pendaftaran
                        }
                        return null;
                    }),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->nullable(),

                // Forms\Components\Repeater::make('produks')
                //     ->relationship('produks') // pivot table sudah benar karena di model sudah fix
                //     ->schema([
                //         Forms\Components\Select::make('id') // pakai 'id' dari Produk
                //             ->label('Produk')
                //             ->options(\App\Models\Produk::all()->pluck('nama', 'id'))
                //             ->required(),

                //         Forms\Components\TextInput::make('jumlah')
                //             ->label('Jumlah')
                //             ->numeric()
                //             ->default(1)
                //             ->required(),
                //     ])
                //     ->label('Produk yang Dibeli')
                //     ->columns(2)
                //     ->createItemButtonLabel('Tambah Produk'),

                // Forms\Components\Repeater::make('produks')
                //     ->relationship('produks') // relasi many-to-many di Tindakan model
                //     ->schema([
                //         Forms\Components\Select::make('produk_id') // gunakan id dari Produk
                //             ->label('Produk')
                //             ->options(\App\Models\Produk::all()->pluck('nama', 'id'))
                //             ->required(),

                //         Forms\Components\TextInput::make('jumlah')
                //             ->label('Jumlah')
                //             ->numeric()
                //             ->default(1)
                //             ->required(),
                //     ])
                //     ->columns(2)
                //     ->createItemButtonLabel('Tambah Produk'),
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

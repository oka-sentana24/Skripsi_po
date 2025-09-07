<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $pluralModelLabel = 'Pembayaran';

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pendaftaran_id')
                    ->label('Pasien')
                    ->options(Pendaftaran::with('pasien')->get()->pluck('pasien.nama', 'id'))
                    ->searchable()
                    ->disabled()
                    ->required(),

                Forms\Components\TextInput::make('total_layanan')
                    ->label('Total Layanan')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                        $diskon = $get('diskon') ?? 0;
                        $set('total_bayar', $state + $totalProduk - $diskon);
                    })
                    ->required(),

                Forms\Components\TextInput::make('diskon')
                    ->label('Diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $totalLayanan = $get('total_layanan') ?? 0;
                        $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                        $set('total_bayar', $totalLayanan + $totalProduk - $state);
                    }),

                Forms\Components\Repeater::make('produk')
                    ->label('Produk')
                    ->columns(4)
                    ->createItemButtonLabel('Tambah Produk')
                    ->relationship('produk') // <-- ini penting supaya data pivot muncul saat edit
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $totalProduk = collect($state ?? [])->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                        $totalLayanan = $get('total_layanan') ?? 0;
                        $diskon = $get('diskon') ?? 0;
                        $set('total_produk', $totalProduk);
                        $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                    })
                    ->schema([
                        Forms\Components\Select::make('produk_id')
                            ->label('Produk')
                            ->options(Produk::all()->pluck('nama', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state) {
                                    $produk = Produk::find($state);
                                    if ($produk) {
                                        $set('harga', $produk->harga ?? 0);
                                        $jumlah = $get('jumlah') ?? 1;
                                        $set('subtotal', $jumlah * $produk->harga);
                                    }
                                }

                                $produkList = $get('produk') ?? [];
                                $totalProduk = collect($produkList)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                                $totalLayanan = $get('total_layanan') ?? 0;
                                $diskon = $get('diskon') ?? 0;
                                $set('total_produk', $totalProduk);
                                $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                            })
                            ->required(),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $jumlah = $state ?? 0;
                                $harga = $get('harga') ?? 0;
                                $set('subtotal', $jumlah * $harga);

                                $produkList = $get('produk') ?? [];
                                $totalProduk = collect($produkList)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                                $totalLayanan = $get('total_layanan') ?? 0;
                                $diskon = $get('diskon') ?? 0;
                                $set('total_produk', $totalProduk);
                                $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                            })
                            ->required(),

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ]),


                Forms\Components\TextInput::make('total_bayar')
                    ->label('Total Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->default(0)
                    ->reactive()
                    ->afterStateHydrated(function ($state, $set, $get) {
                        // Pastikan total_bayar terupdate dari total_layanan + total_produk - diskon saat form load
                        $totalLayanan = $get('total_layanan') ?? 0;
                        $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                        $diskon = $get('diskon') ?? 0;
                        $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.pasien.antrean.nomor_antrean')
                    ->label('Nomor Antrean'),

                Tables\Columns\TextColumn::make('pendaftaran.pasien.nama')
                    ->label('Nama'),

                Tables\Columns\TextColumn::make('total_layanan')->money('IDR'),
                Tables\Columns\TextColumn::make('total_produk')->money('IDR'),
                Tables\Columns\TextColumn::make('diskon')->money('IDR'),
                Tables\Columns\TextColumn::make('total_bayar')->money('IDR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'lunas' => 'Lunas',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    })
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('produk')
                    ->label('Produk')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->produk->map(fn($p) => $p->nama . ' (x' . $p->pivot->jumlah . ')')->join(', ')
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat pada'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Produk;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Rincian Layanan & Produk')
                    ->schema([
                        Forms\Components\Repeater::make('layanans')
                            ->label('Layanan')
                            ->columns(3)
                            ->disableItemDeletion()
                            ->createItemButtonLabel('')
                            ->schema([
                                Forms\Components\TextInput::make('nama_layanan')
                                    ->label('Jenis Layanan')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function ($state, $set, $get, $record) {
                                        if ($record) {
                                            $layanan = $record->pendaftaran->tindakan->flatMap(fn($t) => $t->layanans)->pluck('nama')->join(', ');
                                            $set('nama_layanan', $layanan);
                                        }
                                    }),

                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga Layanan')
                                    ->numeric()
                                    ->disabled()
                                    ->reactive(),
                            ])
                            ->afterStateHydrated(function ($state, $set, $get, $record) {
                                if ($record) {
                                    // Ambil layanan dari tindakan terkait
                                    $layananItems = $record->pendaftaran->tindakan->flatMap(function ($t) {
                                        return $t->layanans->map(fn($l) => [
                                            'tindakan_id' => $t->id,
                                            'nama_layanan' => $l->nama,
                                            'harga' => $l->harga,
                                        ]);
                                    });
                                    $set('layanans', $layananItems->toArray());
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                // Update total layanan
                                $totalLayanan = collect($state ?? [])->sum(fn($item) => (float) ($item['harga'] ?? 0));
                                $set('total_layanan', $totalLayanan);

                                // Update total bayar
                                $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ((int) ($item['jumlah'] ?? 0)) * ((float) ($item['harga'] ?? 0)));
                                $diskon = (float) ($get('diskon') ?? 0);
                                $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                            }),
                        Forms\Components\Repeater::make('produk')
                            ->label('Produk')
                            ->columns(4)
                            ->createItemButtonLabel('Tambah Produk')
                            ->reactive()
                            ->schema([
                                Forms\Components\Select::make('produk_id')
                                    ->label('Produk')
                                    ->options(Produk::all()->pluck('nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state) {
                                            $produk = Produk::find($state);
                                            if ($produk) $set('harga', (float) ($produk->harga ?? 0));
                                            $set('subtotal', ((int) ($get('jumlah') ?? 1)) * ((float) ($produk->harga ?? 0)));
                                        }
                                    }),

                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $set('subtotal', ((int) $state) * ((float) ($get('harga') ?? 0)));
                                    }),

                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->afterStateHydrated(function ($state, $set, $get, $record) {
                                if ($record) {
                                    $set('produk', $record->produk->map(fn($p) => [
                                        'produk_id' => $p->id,
                                        'jumlah' => $p->pivot->jumlah ?? 1,
                                        'harga' => $p->harga,
                                        'subtotal' => ($p->pivot->jumlah ?? 1) * $p->harga,
                                    ])->toArray());
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $totalProduk = collect($state ?? [])->sum(fn($item) => ((int) ($item['jumlah'] ?? 0)) * ((float) ($item['harga'] ?? 0)));
                                $totalLayanan = (float) ($get('total_layanan') ?? 0);
                                $diskon = (float) ($get('diskon') ?? 0);
                                $set('total_produk', $totalProduk);
                                $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                            }),

                        Forms\Components\TextInput::make('total_layanan')
                            ->label('Total Layanan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->reactive()
                            ->dehydrated(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Total Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('diskon')
                            ->label('Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $totalLayanan = (float) ($get('total_layanan') ?? 0);
                                $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ((int) ($item['jumlah'] ?? 0)) * ((float) ($item['harga'] ?? 0)));
                                $set('total_bayar', $totalLayanan + $totalProduk - ((float) $state));
                            }),
                        Forms\Components\TextInput::make('total_bayar')
                            ->label('Total Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->default(0)
                            ->reactive()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $totalLayanan = (float) ($get('total_layanan') ?? 0);
                                $totalProduk = collect($get('produk') ?? [])->sum(fn($item) => ((int) ($item['jumlah'] ?? 0)) * ((float) ($item['harga'] ?? 0)));
                                $diskon = (float) ($get('diskon') ?? 0);
                                $set('total_bayar', $totalLayanan + $totalProduk - $diskon);
                            }),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.pasien.no_rm')
                    ->label('No. RM')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn($record) => "No. Rekam Medik: {$record->pendaftaran->pasien->no_rm}")
                    ->extraAttributes(['class' => 'whitespace-nowrap']),

                Tables\Columns\TextColumn::make('pendaftaran.pasien.nama')
                    ->label('Nama Pasien')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn($record) => "Nama lengkap: {$record->pendaftaran->pasien->nama}")
                    ->extraAttributes(['class' => 'whitespace-nowrap']),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->formatStateUsing(fn($state, $record) => $record->status === 'lunas' ? $record->metode_pembayaran : '-')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->status === 'lunas'
                            ? 'Rp ' . number_format($record->total_bayar, 0, ',', '.')
                            : '-'
                    )
                    ->sortable()
                    ->alignEnd()
                    ->tooltip(fn($record) => $record->status === 'lunas' ? "Bayar: Rp " . number_format($record->total_bayar, 0, ',', '.') : "Belum bayar"),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu_pembayaran' => 'warning',
                        'lunas' => 'success',
                        'dibatalkan' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'lunas' => 'Lunas',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('print_invoice')
                    ->visible(fn($record) => $record->status === 'lunas')
                    ->tooltip('Cetak invoice pembayaran')
                    ->label('')
                    ->icon('heroicon-o-printer')
                    ->modalHeading('Cetak Invoice Pembayaran')
                    ->modalContent(
                        fn($record) =>
                        view('livewire.invoice-show', ['pembayaran' => $record])
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
                Tables\Actions\EditAction::make()
                    ->label(''),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now()),
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(
                            $data['tanggal'] ?? null,
                            fn(Builder $query, $tanggal) =>
                            $query->whereDate('created_at', $tanggal)
                        )
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc'); // urut terbaru di atas
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

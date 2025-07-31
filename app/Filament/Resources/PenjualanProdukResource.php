<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanProdukResource\Pages;
use App\Filament\Resources\PenjualanProdukResource\RelationManagers;
use App\Models\PenjualanProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenjualanProdukResource extends Resource
{
    protected static ?string $model = PenjualanProduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Penjualan Produk';
    protected static ?string $pluralModelLabel = 'Daftar Penjualan Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pendaftaran_id')
                    ->label('Pendaftaran')
                    ->relationship('pendaftaran', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('produk_id')
                    ->label('Produk')
                    ->relationship('produk', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.id')
                    ->label('Pendaftaran ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('produk.nama')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),
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
            'index' => Pages\ListPenjualanProduks::route('/'),
            'create' => Pages\CreatePenjualanProduk::route('/create'),
            'edit' => Pages\EditPenjualanProduk::route('/{record}/edit'),
        ];
    }
}

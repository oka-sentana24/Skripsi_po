<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Data Produk';

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'eksekutif']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(100),

                TextInput::make('harga')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('stok')
                    ->label('Stok')
                    ->required()
                    ->numeric(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('stok')
                    ->label('Stok'),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(30),
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}

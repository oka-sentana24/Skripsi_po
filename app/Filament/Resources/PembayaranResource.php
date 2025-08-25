<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    // protected static ?string $navigationGroup = 'Manajemen Pembayaran';
    // protected static ?string $modelLabel = 'Pembayaran';
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
                    ->relationship('pendaftaran', 'id')
                    ->label('Pendaftaran')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('total_layanan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\TextInput::make('total_produk')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\TextInput::make('diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                Forms\Components\TextInput::make('total_bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.id')
                    ->label('ID Pendaftaran'),

                Tables\Columns\TextColumn::make('total_layanan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_produk')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('diskon')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_bayar')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat pada'),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}

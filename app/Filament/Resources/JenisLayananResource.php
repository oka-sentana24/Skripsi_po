<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisLayananResource\Pages;
use App\Filament\Resources\JenisLayananResource\RelationManagers;
use App\Models\JenisLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisLayananResource extends Resource
{
    protected static ?string $model = JenisLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Jenis Layanan';
    protected static ?string $pluralModelLabel = 'Daftar Jenis Layanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('harga')
                ->label('Harga (Rp)')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\TextInput::make('durasi')
                ->label('Durasi (menit)')
                ->numeric()
                ->nullable(),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(3)
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('nama')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('harga')->money('IDR', true),
            Tables\Columns\TextColumn::make('durasi')->label('Durasi (menit)'),
            Tables\Columns\TextColumn::make('deskripsi')->limit(30),
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
            'index' => Pages\ListJenisLayanans::route('/'),
            'create' => Pages\CreateJenisLayanan::route('/create'),
            'edit' => Pages\EditJenisLayanan::route('/{record}/edit'),
        ];
    }
}

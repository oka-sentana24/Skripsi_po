<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    protected static ?string $modelLabel = 'Pendaftaran';
    protected static ?string $pluralModelLabel = 'Daftar Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('antrean_id')
                    ->label('Antrean')
                    ->relationship('antrean', 'nomor_antrean')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_pendaftaran')
                    ->label('Tanggal Pendaftaran')
                    ->required(),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('antrean.nomor_antrean')
                    ->label('Nomor Antrean')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since(),
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}

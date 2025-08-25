<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TindakanResource\Pages;
use App\Filament\Resources\TindakanResource\RelationManagers;
use App\Models\Tindakan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->label('Pendaftaran')
                    ->relationship('pendaftaran', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('terapis_id')
                    ->label('Terapis')
                    ->relationship('terapis', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('layanan_id')
                    ->label('Jenis Layanan')
                    ->relationship('layanan', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.id')
                    ->label('ID Pendaftaran')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('terapis.nama')
                    ->label('Terapis')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('layanan.nama')
                    ->label('Jenis Layanan')
                    ->sortable()
                    ->searchable(),

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
            'create' => Pages\CreateTindakan::route('/create'),
            'edit' => Pages\EditTindakan::route('/{record}/edit'),
        ];
    }
}

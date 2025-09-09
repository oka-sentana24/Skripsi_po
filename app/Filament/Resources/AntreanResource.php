<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AntreanResource\Pages;
use App\Filament\Resources\AntreanResource\RelationManagers;
use App\Models\Antrean;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AntreanResource extends Resource
{
    protected static ?string $model = Antrean::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Antrean';
    protected static ?string $pluralModelLabel = 'Template Antrean';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pasien_id')
                    ->relationship('pasien', 'nama')
                    ->searchable()
                    ->required()
                    ->label('Pasien'),

                TextInput::make('nomor_antrean')
                    ->required()
                    ->numeric()
                    ->label('Nomor Antrean'),

                DatePicker::make('tanggal_antrean')
                    ->required()
                    ->label('Tanggal Antrean'),

                Select::make('status')
                    ->required()
                    ->options([
                        'menunggu' => 'Menunggu',
                        'dilayani' => 'Dilayani',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                    ])
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pasien.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pasien'),

                TextColumn::make('nomor_antrean')
                    ->sortable()
                    ->label('No. Antrean'),

                TextColumn::make('tanggal_antrean')
                    ->date()
                    ->sortable()
                    ->label('Tanggal'),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'menunggu',
                        'info' => 'dilayani',
                        'success' => 'selesai',
                        'danger' => 'batal',
                    ])
                    ->label('Status'),
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
            'index' => Pages\ListAntreans::route('/'),
            'create' => Pages\CreateAntrean::route('/create'),
            'edit' => Pages\EditAntrean::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // ❌ tidak akan tampil di sidebar
    }
}

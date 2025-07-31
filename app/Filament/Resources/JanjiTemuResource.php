<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JanjiTemuResource\Pages;
use App\Filament\Resources\JanjiTemuResource\RelationManagers;
use App\Models\JanjiTemu;
use App\Models\JenisLayanan;
use App\Models\Terapis;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JanjiTemuResource extends Resource
{
    protected static ?string $model = JanjiTemu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Pasien';
    protected static ?string $modelLabel = 'Janji Temu';
    protected static ?string $pluralModelLabel = 'Daftar Janji Temu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pasien_id')
                ->label('Pasien')
                ->searchable()
                ->options(function () {
                    return \App\Models\Pasien::all()
                        ->mapWithKeys(fn ($pasien) => [
                            $pasien->id => "{$pasien->no_rm} - {$pasien->nama}"
                        ])
                        ->toArray();
                })
                ->getSearchResultsUsing(function (string $search) {
                    return \App\Models\Pasien::query()
                        ->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_rm', 'like', "%{$search}%")
                        ->limit(20)
                        ->get()
                        ->mapWithKeys(fn ($pasien) => [
                            $pasien->id => "{$pasien->no_rm} - {$pasien->nama}"
                        ])
                        ->toArray();
                })
                ->getOptionLabelUsing(function ($value): ?string {
                    $pasien = \App\Models\Pasien::find($value);
                    return $pasien ? "{$pasien->no_rm} - {$pasien->nama}" : null;
                })
                ->required(),

                Forms\Components\DatePicker::make('tanggal_janji')
                ->label('Tanggal Janji')
                ->required()
                ->minDate(Carbon::today())
                ->native(false),

            TimePicker::make('jam_janji')
                ->label('Jam Janji')
                ->format('H:i') // format hanya jam dan menit
                ->withoutSeconds() // hilangkan detik dari picker UI jika didukung
                ->required(),

            BelongsToManyMultiSelect::make('jenisLayanans')
                ->label('Layanan')
                ->relationship('jenisLayanans', 'nama')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('terapis_id')
                ->label('Terapis')
                ->options(Terapis::pluck('nama', 'id')->toArray())
                ->searchable()
                ->placeholder('Pilih layanan')
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pasien.nama')
                ->label('Pasien')
                ->searchable(),

            Tables\Columns\TextColumn::make('tanggal_janji')
                ->label('Tanggal Janji')
                ->date(),

            Tables\Columns\TextColumn::make('jam_janji')
                ->label('Jam Janji')
                ->time(),

            Tables\Columns\TextColumn::make('jenisLayanans.nama')
            ->label('Layanan')
            ->formatStateUsing(fn ($state) => collect($state)->implode(', '))
            ->badge()
            ->wrap(),

            Tables\Columns\TextColumn::make('terapis.nama')
                ->label('Terapis')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListJanjiTemus::route('/'),
            // 'create' => Pages\CreateJanjiTemu::route('/create'),
            // 'edit' => Pages\EditJanjiTemu::route('/{record}/edit'),
        ];
    }
}

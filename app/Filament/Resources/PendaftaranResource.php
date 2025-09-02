<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Antrean;
use App\Models\Pendaftaran;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    // protected static ?string $modelLabel = 'Registrasi';
    protected static ?string $pluralModelLabel = 'Pendaftaran Pasien';

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Info Pendaftaran')
                    ->schema([
                        Forms\Components\Select::make('pasien_id')
                            ->label('Pasien')
                            ->searchable()
                            ->getSearchResultsUsing(
                                fn(string $query) =>
                                \App\Models\Pasien::query()
                                    ->where('nama', 'like', "%{$query}%")
                                    ->orWhere('no_rm', 'like', "%{$query}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn($pasien) => [
                                        $pasien->id => "{$pasien->no_rm} - {$pasien->nama}"
                                    ])
                            )
                            ->getOptionLabelUsing(
                                fn($value): ?string =>
                                optional(\App\Models\Pasien::find($value))->no_rm
                                    . ' - ' .
                                    optional(\App\Models\Pasien::find($value))->nama
                            )
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_pendaftaran')
                            ->label('Tanggal Pendaftaran')
                            ->required(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan / Riwayat')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('antrean.nomor_antrean')
                    ->label('Nomor Antrean')
                    // ->formatStateUsing(fn($state) => $state ?? 'Belum diverifikasi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Pasien')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan / Riwayat')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->limit(50)
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('tanggal_pendaftaran')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now()), // default hari ini
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'] ?? null,
                                fn($q, $date) => $q->whereDate('tanggal_pendaftaran', $date)
                            );
                    })
                    ->default(), // supaya filter aktif default
            ])
            ->actions([
                // aksi lainmu tetap sama
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

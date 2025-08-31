<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Antrean;
use App\Models\Pendaftaran;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    // protected static ?string $modelLabel = 'Registrasi';
    protected static ?string $pluralModelLabel = 'Registrasi Antrian';

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Form $form): Form
    {
        return $form
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
                    ->formatStateUsing(fn($state) => $state ?? 'Belum diverifikasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Pasien')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('verifikasiHadir')
                    ->label('Verifikasi Hadir')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(
                        fn($record) =>
                        in_array($record->status, ['menunggu_verifikasi'])
                    )
                    ->action(function ($record) {
                        // Buat antrean baru
                        $lastNumber = Antrean::whereDate('tanggal_antrean', now()->toDateString())
                            ->max('nomor_antrean') ?? 0;
                        $newAntrean = Antrean::create([
                            'nomor_antrean'   => $lastNumber + 1,
                            'pasien_id'       => $record->pasien_id,
                            'tanggal_antrean' => now()->toDateString(),
                            'status'          => 'menunggu',
                        ]);

                        // Update pendaftaran
                        $record->update([
                            'status' => 'terverifikasi',
                            'antrean_id' => $newAntrean->id,
                        ]);

                        Notification::make()
                            ->title('Pasien telah diverifikasi dan nomor antrean dibuat.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tambahkan aksi verifikasi hadir di sini
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

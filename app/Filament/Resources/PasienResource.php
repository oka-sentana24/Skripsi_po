<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Filament\Resources\PasienResource\RelationManagers;
use App\Models\Pasien;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // protected static ?string $navigationGroup = 'Manajemen Pasien';

    protected static ?string $pluralModelLabel = 'Pendaftaran Pasien';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_rm')
                    ->label('No. Rekam Medis')
                    ->required()
                    ->unique(Pasien::class, 'no_rm', ignoreRecord: true)
                    ->maxLength(20)
                    ->default(fn() => Pasien::generateRekamMedikNumber()) // Generate otomatis
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->rows(3),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false),

                Forms\Components\TextInput::make('no_hp')
                    ->label('No. HP')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rm')->label('No. RM')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')->label('Tgl. Lahir')->date(),
                Tables\Columns\TextColumn::make('no_hp')->label('No. HP'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'],
                                fn(Builder $query, $date) => $query->whereDate('created_at', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_card')
                    // ->label('Cetak Kartu')
                    // ->icon('heroicon-o-printer')
                    // // ->url(fn ($record) => route('pasien.print-card', $record))
                    // ->view('livewire.kartu-pasien')
                    // ->openUrlInNewTab(),
                    ->label('Cetak Kartu')
                    ->icon('heroicon-o-printer')
                    ->modalHeading('Kartu Pasien')
                    ->modalWidth('lg')
                    ->modalContent(function ($record) {
                        return view('livewire.livewire.kartu-pasien', ['pasien' => $record]);
                    })
                    ->modalActions([
                        Action::make('close')
                            ->label('Tutup')
                            ->color('gray')
                            ->close(),
                        Action::make('print')
                            ->label('Cetak Kartu')
                            ->button() // buat tombol biasa
                            ->color('primary')
                            ->extraAttributes(['onclick' => 'window.print()']), // jalankan print browser

                    ]),
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
            'index' => Pages\ListPasiens::route('/'),
            // 'create' => Pages\CreatePasien::route('/create'),
            // 'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }
}

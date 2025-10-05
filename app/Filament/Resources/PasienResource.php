<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Models\Pasien;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class PasienResource extends Resource
{
    // Model utama yang digunakan resource ini
    protected static ?string $model = Pasien::class;

    // Ikon di sidebar navigasi
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Label tunggal & jamak untuk resource
    protected static ?string $modelLabel = 'Pasien';
    protected static ?string $pluralModelLabel = 'Pendaftaran Pasien';

    // Urutan resource di menu navigasi
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin']);
    }


    /**
     * Form input untuk tambah/edit pasien
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pasien')
                    ->description('Lengkapi data pasien di bawah ini')
                    ->columns(2)
                    ->schema([
                        // Nomor rekam medis (auto generate & tidak bisa diubah)
                        Forms\Components\TextInput::make('no_rm')
                            ->label('No. Rekam Medis')
                            ->required()
                            ->unique(Pasien::class, 'no_rm', ignoreRecord: true)
                            ->maxLength(20)
                            ->default(fn() => Pasien::generateRekamMedikNumber())
                            ->disabled()
                            ->dehydrated(),

                        // Nama lengkap pasien
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        // Alamat pasien
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),

                        // Tanggal lahir dengan format khusus
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')   // Format tampilan
                            ->format('Y-m-d')
                            ->suffixIcon('heroicon-o-calendar')
                            ->maxDate(now()->toDateString()),  // Format penyimpanan DB

                        // Nomor HP pasien
                        Forms\Components\TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        // Email pasien
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    /**
     * Tabel daftar pasien
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rm')->label('No. RM')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tgl. Lahir')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('no_hp')->label('No. HP'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter berdasarkan tanggal dibuat
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'],
                            fn(Builder $query, $date) => $query->whereDate('created_at', $date)
                        );
                    }),
            ])
            ->actions([
                // Aksi edit data
                Tables\Actions\EditAction::make(),

                // Aksi cetak kartu pasien
                Tables\Actions\Action::make('print_card')
                    ->label('Cetak Kartu')
                    ->icon('heroicon-o-printer')
                    ->modalHeading('Kartu Pasien')
                    ->modalWidth('lg')
                    ->modalContent(
                        fn($record) =>
                        view('livewire.livewire.kartu-pasien', ['pasien' => $record])
                    )
                    ->modalActions([
                        Action::make('close')
                            ->label('Tutup')
                            ->color('gray')
                            ->close(),

                        Action::make('print')
                            ->label('Cetak Kartu')
                            ->button()
                            ->color('primary')
                            ->extraAttributes(['onclick' => 'window.print()']),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Relasi yang dimiliki resource ini
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Daftar halaman yang tersedia untuk resource ini
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPasiens::route('/'),
            'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('nomor_rekam_medik')
                    ->label('NRM')  // Menambahkan label
                    ->required()  // Validasi wajib
                    ->unique()  // Validasi agar tidak ada duplikat
                    ->maxLength(20)
                    ->default(fn() => \App\Models\Patient::generateRekamMedikNumber()) // Generate otomatis
                    ->disabled(), // Nonaktifkan agar user tidak bisa edit,


                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')  // Menambahkan label
                    ->required()  // Menambahkan validasi wajib
                    ->maxLength(255),  // Validasi panjang maksimal
                    
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')  // Menambahkan label
                    ->options([
                        'pria' => 'Laki - laki',
                        'wanita' => 'Perempuan',
                    ])
                    ->required()  // Validasi wajib
                    ->native(false),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')  // Menambahkan label
                    ->required()  // Validasi wajib
                    ->date()
                    ->native(false),

                TextInput::make('alamat')
                    ->label('Alamat')  // Menambahkan label
                    ->required()  // Validasi wajib
                    ->maxLength(255),

                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')  // Menambahkan label
                    ->required()  // Validasi wajib
                    ->maxLength(15),  // Validasi panjang nomor telepon

                TextInput::make('nomor_ktp')
                    ->label('Nomor KTP')  // Menambahkan label
                    ->required()  // Validasi wajib
                    ->unique()  // Validasi agar tidak ada duplikat
                    ->maxLength(16),

                TextInput::make('nama_kontak_darurat')
                    ->label('Kontak Darurat')  // Menambahkan label
                    ->nullable()  // Tidak wajib
                    ->maxLength(255),

                TextInput::make('hubungan_darurat')
                    ->label('Hubungan Darurat')  // Menambahkan label
                    ->nullable()  // Tidak wajib
                    ->maxLength(100),

                Select::make('status')
                    ->label('Status Pasien')  // Menambahkan label
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                    ])
                    ->required()  // Validasi wajib
                    ->default('aktif')  // Menetapkan nilai default
                    ->native(false),

                Textarea::make('riwayat_penyakit')
                    ->label('Riwayat Penyakit')  // Menambahkan label
                    ->nullable()  // Tidak wajib
                    ->maxLength(500),

                Textarea::make('alergi')
                    ->label('Alergi')  // Menambahkan label
                    ->nullable()  // Tidak wajib
                    ->maxLength(500),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('nomor_rekam_medik'),
                TextColumn::make('nama_lengkap'),
                TextColumn::make('nomor_telepon'),
                TextColumn::make('nomor_ktp'),
                TextColumn::make('riwayat_penyakit'),
                TextColumn::make('alergi'),
                TextColumn::make('nama_kontak_darurat'),
                TextColumn::make('hubungan_darurat'),
                TextColumn::make('created_at'),
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
            'index' => Pages\ListPatients::route('/'),
            // 'create' => Pages\CreatePatient::route('/create'),
            // 'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}

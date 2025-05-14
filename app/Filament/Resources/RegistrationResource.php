<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;
use App\Models\Patient;
use App\Models\Registration;
use Carbon\Carbon;
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
use Filament\Tables\Filters\Filter;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            //
            Select::make('patient_id') // Ensure this matches the database column
                ->label('Pasien') // Changed to 'Pasien' in Indonesian
                ->options(
                    Patient::pluck('nama_lengkap', 'id')->toArray() // Ensure 'nama_lengkap' and 'id' exist in the 'patients' table
                )
                ->relationship('patient', 'nama_lengkap') // Define the relationship if applicable
                ->searchable(),

            Select::make('status')
                ->label('Status') // Optional: Add label if you need one in Indonesian
                ->options([
                    'menunggu' => 'Menunggu',
                    'diperiksa' => 'Diperiksa',
                    'selesai' => 'Selesai',
                ])
                ->default('menunggu')
                ->native(false),

            DatePicker::make('registration_date')
                ->label('Tanggal Pendaftaran') // Custom label for registration date
                ->minDate(now()->format('Y-m-d')) // Ensure the date picker doesn't allow past dates
                ->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('registration_date')
                    ->label('Tanggal Pendaftaran')  // Label in Indonesian for registration date
                    ->date('d-m-Y'),

                TextColumn::make('patient.nama_lengkap')
                    ->label('Nama Pasien'),  // Label in Indonesian for patient's name

                TextColumn::make('queue.queue_number')
                    ->label('Nomor Antrean'),  // Label in Indonesian for queue number

                TextColumn::make('status')
                    ->label('Status')  // Label for status
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'menunggu' => 'warning',  // Yellow for "waiting"
                            'diperiksa' => 'info',    // Blue for "being examined"
                            'selesai' => 'success',   // Green for "completed"
                            default => 'secondary',   // Default color for undefined statuses
                        };
                    }),
            ])
            ->filters([
                //
                Filter::make('today')
                ->label('Registrasi Hari Ini')
                ->query(function ($query) {
                    $query->whereDate('registration_date', today()); // Filter berdasarkan tanggal hari ini
                })
                ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printQueueNumber')
                ->label('Preview Antrean')
                ->modalContent(function ($record) {
                    // Load the related data
                    $record->load(['patient', 'queue']);
                    
                    // Pass the data to the modal view
                    return view('livewire.queue-modal', [
                        'patient' => $record->patient,
                        'queue' => $record->queue,
                    ]);
                })
                ->modalWidth('lg')
                ->icon('heroicon-o-printer')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                
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
            'index' => Pages\ListRegistrations::route('/'),
            // 'create' => Pages\CreateRegistration::route('/create'),
            // 'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}

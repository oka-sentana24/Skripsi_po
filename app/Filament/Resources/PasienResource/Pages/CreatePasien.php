<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Filament\Actions\Action;

class CreatePasien extends CreateRecord
{
    protected static string $resource = PasienResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Pasien';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Tambah Pasien');
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Pasien';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->button()
                ->color('primary')
                ->action(function () {
                    // Simpan record manual
                    $this->record = $this->handleRecordCreation($this->form->getState());

                    // Notifikasi custom
                    $pasien = $this->record->nama ?? 'Pasien';
                    Notification::make()
                        ->title('Berhasil')
                        ->body("Data pasien <b>{$pasien}</b> berhasil ditambahkan.")
                        ->success()
                        ->send();

                    // Redirect ke index
                    return redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('close')
                ->label('Tutup')
                ->button()
                ->color('gray')
                ->action(function () {
                    return redirect($this->getResource()::getUrl('index'));
                })
                ->close(),
        ];
    }
}
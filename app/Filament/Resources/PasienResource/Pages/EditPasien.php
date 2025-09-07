<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditPasien extends EditRecord
{
    protected static string $resource = PasienResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Edit Pasien';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Edit Data Pasien');
    }

    public function getBreadcrumb(): string
    {
        return 'Edit Pasien';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->button()
                ->color('primary')
                ->action(function () {
                    try {
                        // Simpan record manual
                        $this->record->save();

                        // Notifikasi sukses
                        $pasien = $this->record->nama ?? 'Pasien';
                        Notification::make()
                            ->title('Berhasil')
                            ->body("Data pasien <b>{$pasien}</b> berhasil diperbarui.")
                            ->success()
                            ->send();

                        // Redirect ke index
                        return redirect($this->getResource()::getUrl('index'));
                    } catch (\Exception $e) {
                        // Notifikasi gagal
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();

                        throw $e; // hentikan proses save
                    }
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
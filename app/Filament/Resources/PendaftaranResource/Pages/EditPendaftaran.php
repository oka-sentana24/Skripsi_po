<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Edit Registrasi';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Edit Registrasi');
    }

    public function getBreadcrumb(): string
    {
        return 'Edit Registrasi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Registrasi')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Hapus')
                ->modalDescription('Apakah Anda yakin ingin menghapus registrasi ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->successNotification(
                    Notification::make()
                        ->title('Registrasi Dihapus')
                        ->body('Data registrasi pasien berhasil dihapus.')
                        ->success()
                ),
        ];
    }

    // Custom notifikasi setelah edit berhasil
    protected function getSavedNotification(): ?Notification
    {
        $pasien = $this->record->pasien->nama ?? 'Pasien';

        return Notification::make()
            ->title('Update Berhasil')
            ->body("Data registrasi pasien <b>{$pasien}</b> berhasil diperbarui.")
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->button()
                ->color('primary')
                ->action(function () {
                    try {
                        // Pakai save() bawaan Filament biar state form tersimpan
                        $this->save();

                        return redirect($this->getResource()::getUrl('index'));
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();

                        throw $e;
                    }
                }),

            Actions\Action::make('close')
                ->label('Tutup')
                ->button()
                ->color('gray')
                ->action(fn() => redirect($this->getResource()::getUrl('index')))
                ->close(),
        ];
    }
}
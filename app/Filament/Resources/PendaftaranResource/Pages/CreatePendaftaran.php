<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use App\Models\Antrean;
use App\Models\Tindakan;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Registrasi';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Tambah Registrasi');
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Registrasi';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['janji_temu_id'])) {
            // Pendaftaran langsung → buat antrean otomatis
            $antrean = DB::transaction(function () use ($data) {
                $lastNumber = Antrean::whereDate('created_at', now()->toDateString())
                    ->lockForUpdate()
                    ->max('nomor_antrean') ?? 0;

                return Antrean::create([
                    'nomor_antrean' => $lastNumber + 1,
                    'pasien_id' => $data['pasien_id'],
                    'tanggal_antrean' => now()->toDateString(),
                    'status' => 'menunggu',
                ]);
            });

            $data['status'] = 'menunggu';
            $data['antrean_id'] = $antrean->id;
        } else {
            // Dari janji temu
            $data['status'] = 'terverifikasi';
        }

        return $data;
    }

    /**
     * Setelah pendaftaran dibuat → buat tindakan otomatis
     */
    protected function afterCreate(): void
    {
        if ($this->record) {
            Tindakan::create([
                'pendaftaran_id' => $this->record->id,
                'pasien_id'      => $this->record->pasien_id,
                'status'         => 'menunggu',
            ]);
        }
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null; // biar notifikasi bawaan tidak muncul
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->button()
                ->color('primary')
                ->action(function () {
                    try {
                        // langsung jalankan default proses create
                        $this->create();

                        $pasien = $this->record->pasien->nama ?? 'Pasien';

                        Notification::make()
                            ->title('Registrasi Berhasil')
                            ->body("Registrasi untuk pasien <b>{$pasien}</b> berhasil dibuat.")
                            ->success()
                            ->send();

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
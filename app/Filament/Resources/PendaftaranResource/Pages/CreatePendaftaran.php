<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use App\Models\Antrean;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Cek apakah ini dari janji temu atau langsung
        if (empty($data['janji_temu_id'])) {
            // Pendaftaran langsung → langsung dapat nomor antrean
            $lastNumber = Antrean::whereDate('created_at', now()->toDateString())->max('nomor_antrean') ?? 0;

            $antrean = Antrean::create([
                'nomor_antrean' => $lastNumber + 1,
                'pasien_id' => $data['pasien_id'],
                'tanggal_antrean' => now()->toDateString(),
                'status' => 'menunggu',
            ]);

            // update status + link antrean
            $data['status'] = 'terverifikasi';
            $data['antrean_id'] = $antrean->id;
        } else {
            // Dari janji temu → butuh verifikasi
            $data['status'] = 'menunggu_verifikasi';
        }

        return $data;
    }

    // protected function afterCreate(): void
    // {
    //     Notification::make()
    //         ->title('Pendaftaran berhasil disimpan.')
    //         ->success()
    //         ->send();
    // }
}

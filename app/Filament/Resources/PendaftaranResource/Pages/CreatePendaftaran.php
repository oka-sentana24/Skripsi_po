<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use App\Models\Antrean;
use App\Models\Tindakan;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     if (empty($data['janji_temu_id'])) {
    //         // Pendaftaran langsung → buat antrean otomatis
    //         $antrean = DB::transaction(function () use ($data) {
    //             $lastNumber = Antrean::whereDate('created_at', now()->toDateString())
    //                 ->lockForUpdate()
    //                 ->max('nomor_antrean') ?? 0;

    //             return Antrean::create([
    //                 'nomor_antrean' => $lastNumber + 1,
    //                 'pasien_id' => $data['pasien_id'],
    //                 'tanggal_antrean' => now()->toDateString(),
    //                 'status' => 'menunggu',
    //             ]);
    //         });

    //         $data['status'] = 'terverifikasi';
    //         $data['antrean_id'] = $antrean->id;
    //     } else {
    //         // Dari janji temu
    //         $data['status'] = 'terverifikasi';
    //     }

    //     return $data;
    // }

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

            $data['status'] = 'terverifikasi';
            $data['antrean_id'] = $antrean->id;
        } else {
            // Dari janji temu
            $data['status'] = 'terverifikasi';
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $pendaftaran = $this->record;

        // Buat pemeriksaan/tindakan otomatis
        Tindakan::create([
            'pendaftaran_id' => $pendaftaran->id,
            'terapis_id' => null, // Bisa nanti diisi saat terapis ditentukan
            'layanan_id' => null, // Bisa diisi sesuai layanan default atau janji temu
            'catatan' => 'Pemeriksaan awal dibuat otomatis',
        ]);
    }
}

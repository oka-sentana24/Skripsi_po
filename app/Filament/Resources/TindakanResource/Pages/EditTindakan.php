<?php

namespace App\Filament\Resources\TindakanResource\Pages;

use App\Filament\Resources\TindakanResource;
use App\Models\Tindakan;
use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\EditRecord;

class EditTindakan extends EditRecord
{
    protected static string $resource = TindakanResource::class;

    protected function afterSave(): void
    {
        $tindakan = $this->record;
        $pendaftaran = $tindakan->pendaftaran;

        // --- 1. Simpan produk ke pivot table ---
        $produkState = $this->form->getState()['produks'] ?? [];

        $syncData = collect($produkState)->mapWithKeys(function ($item) {
            return [$item['produk_id'] => ['jumlah' => $item['jumlah']]];
        })->toArray();

        $tindakan->produks()->sync($syncData);

        // --- 2. Hitung total layanan & produk dari semua tindakan pendaftaran ---
        $totalLayanan = $pendaftaran->tindakans->sum(function ($t) {
            return $t->layanans->sum('harga');
        });

        $totalProduk = $pendaftaran->tindakans->sum(function ($t) {
            return $t->produks->sum(function ($produk) {
                return $produk->harga * $produk->pivot->jumlah;
            });
        });

        // --- 3. Buat atau update pembayaran ---
        if ($pendaftaran->pembayaran) {
            $pendaftaran->pembayaran()->update([
                'total_layanan' => $totalLayanan,
                'total_produk' => $totalProduk,
                'total_bayar' => $totalLayanan + $totalProduk,
            ]);
        } else {
            $pendaftaran->pembayaran()->create([
                'total_layanan' => $totalLayanan,
                'total_produk' => $totalProduk,
                'diskon' => 0,
                'total_bayar' => $totalLayanan + $totalProduk,
            ]);
        }
    }
}

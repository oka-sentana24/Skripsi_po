<?php

namespace App\Filament\Resources\TindakanResource\Pages;

use App\Filament\Resources\TindakanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditTindakan extends EditRecord
{
    protected static string $resource = TindakanResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Edit Pemeriksaan';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Edit Pemeriksaan');
    }

    public function getBreadcrumb(): string
    {
        return 'Edit Pemeriksaan';
    }

    protected function getSavedNotification(): ?Notification
    {
        return null; // matikan notifikasi bawaan edit
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan')
                ->button()
                ->color('primary')
                ->action(function () {
                    try {
                        $this->save(); // Simpan data tindakan
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

    protected function afterSave(): void
    {
        $tindakan = $this->record;
        $pendaftaran = $tindakan->pendaftaran;

        // --- 1. Simpan produk ke pivot table ---
        $produkState = $this->form->getState()['produks'] ?? [];
        $syncData = collect($produkState)->mapWithKeys(fn($item) => [
            $item['produk_id'] => ['jumlah' => $item['jumlah']],
        ])->toArray();
        $tindakan->produks()->sync($syncData);

        // --- 2. Hitung total layanan & produk ---
        $totalLayanan = $pendaftaran->tindakans->sum(fn($t) => $t->layanans->sum('harga'));
        $totalProduk  = $pendaftaran->tindakans->sum(fn($t) => $t->produks->sum(
            fn($produk) => $produk->harga * $produk->pivot->jumlah
        ));
        $totalBayar   = $totalLayanan + $totalProduk;

        // --- 3. Buat atau update pembayaran ---
        if ($pendaftaran->pembayaran) {
            $pendaftaran->pembayaran()->update([
                'total_layanan' => $totalLayanan,
                'total_produk'  => $totalProduk,
                'total_bayar'   => $totalBayar,
                'status'        => 'menunggu_pembayaran',
            ]);
        } else {
            $pendaftaran->pembayaran()->create([
                'total_layanan' => $totalLayanan,
                'total_produk'  => $totalProduk,
                'diskon'        => 0,
                'total_bayar'   => $totalBayar,
                'status'        => 'menunggu_pembayaran',
            ]);
        }

        // --- 4. Set status tindakan menjadi selesai ---
        $tindakan->update([
            'status' => 'selesai',
        ]);

        // --- 5. Notifikasi utama ---
        Notification::make()
            ->title('Berhasil')
            ->body("Tindakan untuk pasien <b>{$pendaftaran->pasien->nama}</b> berhasil disimpan dan selesai.")
            ->success()
            ->send();
    }
}

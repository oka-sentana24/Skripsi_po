<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembayaran extends EditRecord
{
    protected static string $resource = PembayaranResource::class;

    protected array $produkTemp = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalProduk = collect($data['produk'] ?? [])
            ->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));

        $data['total_produk'] = $totalProduk;
        $data['total_bayar'] = ($data['total_layanan'] ?? 0) + $totalProduk - ($data['diskon'] ?? 0);

        // simpan sementara di properti halaman (bukan di DB)
        $this->produkTemp = $data['produk'] ?? [];

        return $data;
    }

    // protected function afterSave(): void
    // {
    //     $pembayaran = $this->record;

    //     if (!empty($this->produkTemp)) {
    //         // kembalikan stok lama
    //         foreach ($pembayaran->produk as $oldProduk) {
    //             $oldProduk->stok += $oldProduk->pivot->jumlah;
    //             $oldProduk->save();
    //         }

    //         // reset pivot
    //         $pembayaran->produk()->detach();

    //         // attach produk baru & kurangi stok
    //         foreach ($this->produkTemp as $item) {
    //             $produk = \App\Models\Produk::find($item['produk_id']);
    //             if ($produk) {
    //                 $jumlah = $item['jumlah'] ?? 0;
    //                 $harga = $item['harga'] ?? 0; // gunakan harga input user saja

    //                 $pembayaran->produk()->attach($produk->id, [
    //                     'jumlah' => $jumlah,
    //                     'harga'  => $harga,
    //                 ]);

    //                 // kurangi stok produk
    //                 $produk->stok = max($produk->stok - $jumlah, 0);
    //                 $produk->save();
    //             }
    //         }
    //     }
    // }

    protected function afterSave(): void
    {
        $pembayaran = $this->record;

        if (!empty($this->produkTemp)) {
            // kembalikan stok lama
            foreach ($pembayaran->produk as $oldProduk) {
                $oldProduk->stok += $oldProduk->pivot->jumlah;
                $oldProduk->save();
            }

            // reset pivot
            $pembayaran->produk()->detach();

            // attach produk baru & kurangi stok
            foreach ($this->produkTemp as $item) {
                $produk = \App\Models\Produk::find($item['produk_id']);
                if ($produk) {
                    $jumlah = $item['jumlah'] ?? 0;
                    $harga = $item['harga'] ?? 0;

                    $pembayaran->produk()->attach($produk->id, [
                        'jumlah' => $jumlah,
                        'harga'  => $harga,
                    ]);

                    // kurangi stok produk
                    $produk->stok = max($produk->stok - $jumlah, 0);
                    $produk->save();
                }
            }
        }

        // --- Tambahkan ini untuk otomatis ubah status ---
        $pembayaran->status = 'lunas';
        $pembayaran->save();
    }




    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

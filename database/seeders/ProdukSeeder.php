<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkList = [
            [
                'nama' => 'Facial Wash',
                'harga' => 55000,
                'stok' => 50,
                'deskripsi' => 'Sabun wajah untuk semua jenis kulit.',
                'exp_date' => now()->addMonths(12),
            ],
            [
                'nama' => 'Moisturizer Gel',
                'harga' => 75000,
                'stok' => 30,
                'deskripsi' => 'Melembapkan kulit tanpa membuat berminyak.',
                'exp_date' => now()->addMonths(10),
            ],
            [
                'nama' => 'Sunscreen SPF 30',
                'harga' => 68000,
                'stok' => 40,
                'deskripsi' => 'Perlindungan dari sinar UVA/UVB.',
                'exp_date' => now()->addMonths(18),
            ],
            [
                'nama' => 'Serum Vitamin C',
                'harga' => 98000,
                'stok' => 20,
                'deskripsi' => 'Mencerahkan dan menyamarkan noda hitam.',
                'exp_date' => now()->addMonths(8),
            ],
        ];

        foreach ($produkList as $produk) {
            Produk::create($produk);
        }
    }
}

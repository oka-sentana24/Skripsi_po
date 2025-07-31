<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use App\Models\PenjualanProduk;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenjualanProduk>
 */
class PenjualanProdukFactory extends Factory
{
    protected $model = PenjualanProduk::class;

    public function definition(): array
    {
        $jumlah = $this->faker->numberBetween(1, 5);
        $harga = $this->faker->randomFloat(2, 10000, 50000);
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'produk_id' => Produk::factory(),
            'jumlah' => $jumlah,
            'harga_satuan' => $harga,
            'subtotal' => $jumlah * $harga,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        $totalLayanan = $this->faker->randomFloat(2, 100_000, 300_000);
        $totalProduk = $this->faker->randomFloat(2, 50_000, 200_000);
        $diskon = $this->faker->randomFloat(2, 0, 50_000);
        $totalBayar = ($totalLayanan + $totalProduk) - $diskon;

        return [
            'pendaftaran_id' => Pendaftaran::factory(), // atau sesuaikan jika data sudah ada
            'total_layanan' => $totalLayanan,
            'total_produk' => $totalProduk,
            'diskon' => $diskon,
            'total_bayar' => $totalBayar,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Antrean;
use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Antrean>
 */
class AntreanFactory extends Factory
{
    protected $model = Antrean::class;

    public function definition(): array
    {
        return [
            'pasien_id' => Pasien::factory(), // pastikan kamu sudah punya PasienFactory
            'nomor_antrean' => $this->faker->unique()->numberBetween(1, 100),
            'tanggal_antrean' => $this->faker->date(),
            'status' => $this->faker->randomElement(['menunggu', 'dilayani', 'selesai', 'batal']),
        ];
    }
}

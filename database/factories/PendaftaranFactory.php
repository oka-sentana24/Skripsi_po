<?php

namespace Database\Factories;

use App\Models\Antrean;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pendaftaran>
 */
class PendaftaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'antrean_id' => Antrean::factory(),
            'tanggal_pendaftaran' => $this->faker->date(),
            'catatan' => $this->faker->sentence(),
        ];
    }
}

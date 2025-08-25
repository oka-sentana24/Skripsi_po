<?php

namespace Database\Factories;

use App\Models\JenisLayanan;
use App\Models\Pendaftaran;
use App\Models\Terapis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tindakan>
 */
class TindakanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'terapis_id' => Terapis::factory(),
            'layanan_id' => JenisLayanan::factory(),
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }
}

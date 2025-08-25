<?php

namespace Database\Factories;

use App\Models\JanjiTemu;
use App\Models\Pasien;
use App\Models\Terapis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JanjiTemu>
 */
class JanjiTemuFactory extends Factory
{
    protected $model = JanjiTemu::class;

    public function definition(): array
    {
        return [
            'pasien_id' => Pasien::factory(),
            'tanggal_janji' => $this->faker->date(),
            'jam_janji' => $this->faker->time(),
            'layanan' => $this->faker->randomElement(['Facial', 'Totok Wajah', 'Massage']),
            'terapis_id' => Terapis::factory(),
        ];
    }

}

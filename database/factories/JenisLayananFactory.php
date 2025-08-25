<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisLayanan>
 */
class JenisLayananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->words(2, true),
            'harga' => $this->faker->randomFloat(2, 50000, 300000),
            'durasi' => $this->faker->numberBetween(30, 120),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}

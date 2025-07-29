<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'harga' => $this->faker->randomFloat(2, 10000, 500000), // harga antara 10rb - 500rb
            'stok' => $this->faker->numberBetween(10, 100), // stok antara 10 - 100
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}

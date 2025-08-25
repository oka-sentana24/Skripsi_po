<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pasien>
 */
class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   protected $model = Pasien::class;

    public function definition(): array
    {
        return [
            'no_rm' => null, // akan otomatis terisi di model
            'nama' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2005-01-01'),
            'no_hp' => '08' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}

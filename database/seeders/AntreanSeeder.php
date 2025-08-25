<?php

namespace Database\Seeders;

use App\Models\Antrean;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AntreanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Antrean::factory()->count(20)->create(); 
    }
}

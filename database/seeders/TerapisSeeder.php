<?php

namespace Database\Seeders;

use App\Models\Terapis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TerapisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Terapis::factory()->count(10)->create();
    }
}

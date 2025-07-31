<?php

namespace Database\Seeders;

use App\Models\PenjualanProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjualanProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenjualanProduk::factory()->count(10)->create();
    }
}

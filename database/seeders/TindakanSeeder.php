<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use App\Models\Pendaftaran;
use App\Models\Terapis;
use App\Models\Tindakan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TindakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data yang tersedia di tabel pendaftaran, terapis, dan jenis layanan
        if (Pendaftaran::count() === 0 || Terapis::count() === 0 || JenisLayanan::count() === 0) {
            $this->command->warn('Pastikan pendaftaran, terapis, dan jenis layanan sudah ada sebelum men-seed tindakan.');
            return;
        }

        // Seed 10 tindakan acak
        Tindakan::factory()
            ->count(10)
            ->create();
    }
}

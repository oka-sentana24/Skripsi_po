<?php

namespace Database\Seeders;

use App\Models\Terapis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerapisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('terapis')->insert([
            [
                'nama' => 'Rina Andini',
                'no_telepon' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sari Oktaviani',
                'no_telepon' => '082233445566',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dewi Lestari',
                'no_telepon' => '089998887766',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

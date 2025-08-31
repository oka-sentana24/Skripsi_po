<?php

namespace Database\Seeders;

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
            [
                'nama' => 'Maya Fitriani',
                'no_telepon' => '081356789012',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lina Pratiwi',
                'no_telepon' => '085677889900',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Anisa Putri',
                'no_telepon' => '081223344556',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Indah Puspita',
                'no_telepon' => '082198765432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ratna Sari',
                'no_telepon' => '081998877665',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Nita Anggraini',
                'no_telepon' => '085234567123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Yuni Kartika',
                'no_telepon' => '089812345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

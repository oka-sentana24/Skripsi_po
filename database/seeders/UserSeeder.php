<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.test',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
    }
}

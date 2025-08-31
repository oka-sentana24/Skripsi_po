<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namaFix = [
            'Ni Luh Ayu Kartini',
            'Ni Kadek Ayu Dewi',
            'Ni Komang Ayu Sulastri',
            'Ni Made Ayu Putri',
            'Kadek Ayu Candra',
            'Putu Ayu Saraswati',
            'Luh Putri Cempaka',
            'Gusti Ayu Widiasih',
            'Luh Sri Utami',
            'Ni Wayan Ayu Ratna',
            'Ni Ketut Ayu Lestari',
            'Luh Citra Paramita',
            'I Made Suardana',
            'I Komang Wirawan',
            'I Kadek Pratama',
            'I Putu Gunawan',
            'Gede Sudarma',
            'Putu Mahendra',
            'Komang Adi Saputra',
            'Wayan Adnyana',
            'Ketut Arya',
            'Ni Luh Ayu Krisna',
            'Ni Kadek Ayu Ratih',
            'Ni Komang Ayu Santi',
            'Ni Made Ayu Merta',
            'Kadek Ayu Ningsih',
            'Putu Ayu Cahyani',
            'Luh Putri Indah',
            'Gusti Ayu Sulastri',
            'Luh Sri Ningsih',
            'Ni Wayan Ayu Laksmi',
            'Ni Ketut Ayu Rahayu',
            'Luh Citra Dewi',
            'I Made Artana',
            'I Komang Arta',
            'I Kadek Putra',
            'I Putu Riana',
            'Gede Susila',
            'Putu Jaya',
            'Komang Rudi',
            'Wayan Sutrisna',
            'Ketut Dharma',
            'Ni Luh Ayu Widya',
            'Ni Kadek Ayu Rini',
            'Ni Komang Ayu Lestari',
            'Ni Made Ayu Pertiwi',
            'Kadek Ayu Yuli',
            'Putu Ayu Andini',
            'Luh Putri Astuti',
            'Gusti Ayu Maharani',
        ];

        $alamat = [
            'Denpasar, Bali',
            'Badung, Bali',
            'Gianyar, Bali',
            'Tabanan, Bali',
        ];

        // Variasi jumlah pasien per bulan (total 50)
        $distribusi = [
            1 => 8,
            2 => 2,
            3 => 12,
            4 => 3,
            5 => 10,
            6 => 5,
            7 => 7,
            8 => 3,
        ];

        $tahun = Carbon::now()->year;
        $index = 0;

        foreach ($distribusi as $bulan => $jumlah) {
            for ($i = 0; $i < $jumlah; $i++) {
                if (!isset($namaFix[$index])) break;

                $tanggal = Carbon::create($tahun, $bulan, rand(1, 28), rand(8, 20), rand(0, 59), 0);

                Pasien::create([
                    'nama' => $namaFix[$index],
                    'alamat' => $alamat[array_rand($alamat)],
                    'tanggal_lahir' => Carbon::create(rand(1970, 2003), rand(1, 12), rand(1, 28)),
                    'no_hp' => '08' . rand(111111111, 999999999),
                    'email' => strtolower(str_replace(' ', '', $namaFix[$index])) . '@mail.com',
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                $index++;
            }
        }
    }
}

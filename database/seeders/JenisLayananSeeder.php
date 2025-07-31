<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $layanans = [
            [
                'nama' => 'Facial Wajah',
                'harga' => 150000,
                'durasi' => 60,
                'deskripsi' => 'Perawatan pembersihan wajah untuk kulit cerah dan sehat.'
            ],
            [
                'nama' => 'Totok Wajah',
                'harga' => 80000,
                'durasi' => 45,
                'deskripsi' => 'Pijatan wajah untuk relaksasi dan melancarkan peredaran darah.'
            ],
            [
                'nama' => 'Laser Jerawat',
                'harga' => 250000,
                'durasi' => 40,
                'deskripsi' => 'Teknologi laser untuk menghilangkan jerawat dan bekasnya.'
            ],
            [
                'nama' => 'Chemical Peeling',
                'harga' => 200000,
                'durasi' => 50,
                'deskripsi' => 'Mengangkat sel kulit mati dan memperbaiki tekstur kulit.'
            ],
            [
                'nama' => 'Microneedling',
                'harga' => 350000,
                'durasi' => 90,
                'deskripsi' => 'Perawatan untuk regenerasi kulit dan menyamarkan bekas luka.'
            ],
            [
                'nama' => 'Body Spa',
                'harga' => 180000,
                'durasi' => 75,
                'deskripsi' => 'Perawatan tubuh untuk relaksasi dan memperlancar peredaran darah.'
            ],
            [
                'nama' => 'Whitening Infusion',
                'harga' => 400000,
                'durasi' => 30,
                'deskripsi' => 'Infus vitamin untuk mencerahkan kulit dari dalam.'
            ],
            [
                'nama' => 'IPL Rejuvenation',
                'harga' => 300000,
                'durasi' => 60,
                'deskripsi' => 'Teknologi cahaya untuk peremajaan kulit wajah.'
            ],
        ];

        foreach ($layanans as $layanan) {
            JenisLayanan::create($layanan);
        }
    }

}

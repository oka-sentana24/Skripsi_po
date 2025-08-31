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
            // Facial
            ['nama' => 'Facial Basic', 'deskripsi' => 'Perawatan dasar wajah', 'harga' => 150000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Facial Acne', 'deskripsi' => 'Facial khusus kulit berjerawat', 'harga' => 200000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Facial Brightening', 'deskripsi' => 'Mencerahkan kulit wajah', 'harga' => 250000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Hydrafacial', 'deskripsi' => 'Perawatan hidrasi wajah modern', 'harga' => 500000, 'created_at' => now(), 'updated_at' => now()],

            // Peeling
            ['nama' => 'Chemical Peeling', 'deskripsi' => 'Mengangkat sel kulit mati', 'harga' => 400000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Whitening Peeling', 'deskripsi' => 'Peeling untuk mencerahkan wajah', 'harga' => 450000, 'created_at' => now(), 'updated_at' => now()],

            // Laser
            ['nama' => 'Laser Rejuvenation', 'deskripsi' => 'Laser untuk peremajaan kulit', 'harga' => 800000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Laser Hair Removal', 'deskripsi' => 'Menghilangkan rambut halus dengan laser', 'harga' => 600000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Fractional CO2 Laser', 'deskripsi' => 'Mengatasi bekas jerawat & flek', 'harga' => 1200000, 'created_at' => now(), 'updated_at' => now()],

            // Injection
            ['nama' => 'Botox Injection', 'deskripsi' => 'Mengurangi kerutan halus', 'harga' => 1500000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Filler Injection', 'deskripsi' => 'Membentuk wajah dengan filler', 'harga' => 2000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Skin Booster', 'deskripsi' => 'Injeksi untuk melembabkan dan memperbaiki kulit', 'harga' => 1800000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Whitening Injection', 'deskripsi' => 'Injeksi pencerah kulit', 'harga' => 1000000, 'created_at' => now(), 'updated_at' => now()],

            // Anti-aging
            ['nama' => 'Thread Lift', 'deskripsi' => 'Tanam benang untuk mengencangkan wajah', 'harga' => 3000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'HIFU Treatment', 'deskripsi' => 'Mengencangkan kulit dengan ultrasound', 'harga' => 2500000, 'created_at' => now(), 'updated_at' => now()],

            // Body
            ['nama' => 'Body Slimming', 'deskripsi' => 'Mengurangi lemak tubuh', 'harga' => 2000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Cryolipolysis', 'deskripsi' => 'Fat freezing untuk menghilangkan lemak', 'harga' => 2500000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Body Whitening', 'deskripsi' => 'Perawatan mencerahkan kulit tubuh', 'harga' => 1200000, 'created_at' => now(), 'updated_at' => now()],

            // Hair & scalp
            ['nama' => 'PRP Hair Growth', 'deskripsi' => 'Terapi PRP untuk menumbuhkan rambut', 'harga' => 2200000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Hair Spa', 'deskripsi' => 'Perawatan rambut dan kulit kepala', 'harga' => 300000, 'created_at' => now(), 'updated_at' => now()],

            // Skin
            ['nama' => 'Microneedling', 'deskripsi' => 'Perawatan dengan dermapen untuk regenerasi kulit', 'harga' => 1200000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'PRP Wajah', 'deskripsi' => 'Platelet Rich Plasma untuk wajah glowing', 'harga' => 2000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'BB Glow', 'deskripsi' => 'Perawatan semi permanen untuk wajah glowing', 'harga' => 1000000, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($layanans as $layanan) {
            JenisLayanan::create($layanan);
        }
    }
}

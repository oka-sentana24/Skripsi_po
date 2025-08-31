<?php

namespace Database\Seeders;

use App\Models\JanjiTemu;
use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JanjiTemuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pasienIds = Pasien::pluck('id')->toArray();
        $terapisIds = DB::table('terapis')->pluck('id')->toArray();

        if (empty($pasienIds) || empty($terapisIds)) {
            $this->command->warn('Seeder JanjiTemu dilewati karena belum ada pasien atau terapis.');
            return;
        }

        $statusOptions = ['dijadwalkan', 'diproses', 'hadir', 'selesai', 'tidak_hadir', 'dibatalkan'];

        for ($i = 0; $i < 30; $i++) {
            $jam = Carbon::createFromTime(rand(8, 16), rand(0, 59), 0)->format('H:i:s');

            JanjiTemu::create([
                'pasien_id'     => $pasienIds[array_rand($pasienIds)],
                'terapis_id'    => $terapisIds[array_rand($terapisIds)],
                'tanggal_janji' => Carbon::today()->format('Y-m-d'),
                'jam_janji'     => $jam,
                'status'        => $statusOptions[array_rand($statusOptions)],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('Seeder JanjiTemu selesai: 30 janji temu hari ini telah dibuat.');
    }
}

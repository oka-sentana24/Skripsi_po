<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JanjiTemu;
use App\Models\Pendaftaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConvertJanjiTemuToPendaftaran extends Command
{
    protected $signature = 'convert:janji-temu';
    protected $description = 'Ubah janji temu yang tanggalnya sudah lewat menjadi pendaftaran dan ubah status menjadi sudah datang';

    public function handle(): int
    {
        $today = Carbon::today('Asia/Jakarta');

        // Ambil janji temu yang sudah lewat dan belum punya pendaftaran
        $janjiTemus = JanjiTemu::whereDate('tanggal_janji', '<=', $today)
            ->whereDoesntHave('pendaftaran')
            ->get();

        if ($janjiTemus->isEmpty()) {
            $this->info("Tidak ada janji temu yang perlu diproses.");
            return self::SUCCESS;
        }

        foreach ($janjiTemus as $janjiTemu) {
            DB::beginTransaction();

            try {
                // Buat pendaftaran tanpa antrean
                Pendaftaran::create([
                    'antrean_id' => null,
                    'pasien_id' => $janjiTemu->pasien_id,
                    'tanggal_pendaftaran' => now(),
                    'catatan' => $janjiTemu->catatan ?? null,
                    'janji_temu_id' => $janjiTemu->id,
                    'status' => 'menunggu_verifikasi', // ✅ tambahkan ini
                ]);

                // Update status janji temu menjadi 'sudah_datang' (menunggu verifikasi admin)
                $janjiTemu->update([
                    'status' => 'diproses',
                ]);

                DB::commit();

                $this->info("✅ Pendaftaran dibuat TANPA antrean untuk Janji Temu ID: {$janjiTemu->id}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Gagal memproses Janji Temu ID: {$janjiTemu->id} - " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}

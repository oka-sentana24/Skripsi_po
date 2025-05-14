<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'nomor_telepon',
        'email',
        'nomor_ktp',
        'nomor_rekam_medik',
        'golongan_darah',
        'riwayat_penyakit',
        'alergi',
        'nama_kontak_darurat',
        'hubungan_darurat',
        'status',
    ];

     // ✅ Pastikan nomor_rekam_medik dibuat sebelum menyimpan data
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->nomor_rekam_medik)) { // Cek jika belum ada
                $patient->nomor_rekam_medik = self::generateRekamMedikNumber();
            }
        });
    }

    // ✅ Fungsi untuk generate nomor rekam medis otomatis
    public static function generateRekamMedikNumber()
    {
        $kodeKlinik = "AIRE"; // Bisa diganti sesuai nama klinik
        $yearMonth = date('Ym'); // Tahun dan Bulan saat ini
        $lastPatient = self::where('nomor_rekam_medik', 'LIKE', "$kodeKlinik-$yearMonth%")
                            ->latest('id')
                            ->first();

        $lastId = $lastPatient ? intval(substr($lastPatient->nomor_rekam_medik, -4)) : 0;
        return $kodeKlinik . '-' . $yearMonth . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }
}

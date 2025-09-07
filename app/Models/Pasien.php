<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rm',
        'nama',
        'alamat',
        'tanggal_lahir',
        'no_hp',
        'email',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke JanjiTemu
     */
    public function janjiTemu(): HasMany
    {
        return $this->hasMany(JanjiTemu::class);
    }

    /**
     * Relasi ke Antrean
     */
    public function antrean(): HasMany
    {
        return $this->hasMany(Antrean::class);
    }

    /**
     * Event model booted
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pasien) {
            if (empty($pasien->no_rm)) {
                $pasien->no_rm = self::generateRekamMedikNumber();
            }
        });
    }

    /**
     * Generate nomor rekam medik unik (berkelanjutan selamanya)
     */
    public static function generateRekamMedikNumber(): string
    {
        $kodeKlinik = "AIRE";

        // Ambil pasien terakhir berdasarkan ID (yang terbaru dibuat)
        $lastPatient = self::orderByDesc('id')->first();

        // Ambil angka urut terakhir, default 0 kalau belum ada pasien
        $lastId = $lastPatient ? intval(substr($lastPatient->no_rm, -6)) : 0;

        // Format nomor RM baru (6 digit angka berurutan)
        return sprintf('%s-%06d', $kodeKlinik, $lastId + 1);
    }
}

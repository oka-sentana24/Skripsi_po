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
     * Generate nomor rekam medik unik
     */
    public static function generateRekamMedikNumber(): string
    {
        $kodeKlinik = "AIRE";
        $yearMonth = date('Ym');

        $lastPatient = self::where('no_rm', 'LIKE', "$kodeKlinik-$yearMonth%")
            ->orderByDesc('id')
            ->first();

        $lastId = $lastPatient ? intval(substr($lastPatient->no_rm, -4)) : 0;

        return sprintf('%s-%s-%04d', $kodeKlinik, $yearMonth, $lastId + 1);
    }
}

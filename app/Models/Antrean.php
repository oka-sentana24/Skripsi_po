<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    use HasFactory;

    protected $fillable = ['pasien_id', 'nomor_antrean', 'tanggal_antrean', 'status'];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }
}

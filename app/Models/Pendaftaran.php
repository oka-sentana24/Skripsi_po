<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = ['pasien_id', 'antrean_id', 'tanggal_pendaftaran', 'catatan', 'janji_temu_id', 'status'];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }


    public function antrean()
    {
        return $this->belongsTo(Antrean::class);
    }

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class);
    }

    public function penjualanProduk()
    {
        return $this->hasMany(PenjualanProduk::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
    public function janjiTemu()
    {
        return $this->belongsTo(JanjiTemu::class);
    }
}

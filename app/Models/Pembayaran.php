<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'total_layanan',
        'total_produk',
        'diskon',
        'total_bayar',
        'status',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function produk()
    {
        return $this->belongsToMany(\App\Models\Produk::class, 'pembayaran_produk')
            ->withPivot('jumlah', 'harga')
            ->withTimestamps();
    }
}
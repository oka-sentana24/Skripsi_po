<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    use HasFactory;

    protected $fillable = ['pendaftaran_id', 'produk_id', 'jumlah', 'harga_satuan', 'subtotal'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}

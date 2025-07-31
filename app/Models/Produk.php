<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'harga', 'stok', 'deskripsi', 'exp_date'];

    public function penjualanProduk()
    {
        return $this->hasMany(PenjualanProduk::class);
    }
}

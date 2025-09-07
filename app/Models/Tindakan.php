<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'terapis_id',
        'layanan_id',
        'catatan',
        'status',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class, 'terapis_id');
    }

    public function layanans()
    {
        return $this->belongsToMany(
            JenisLayanan::class,
            'tindakan_layanan', // nama pivot table
            'tindakan_id',      // foreign key pivot untuk Tindakan
            'layanan_id'        // foreign key pivot untuk JenisLayanan
        );
    }

    public function produks()
    {
        return $this->belongsToMany(\App\Models\Produk::class, 'tindakan_produk')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
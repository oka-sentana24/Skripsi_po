<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        'durasi',
        'deskripsi',
    ];

    public function janjiTemus()
    {
        return $this->belongsToMany(JanjiTemu::class, 'janji_temu_layanan');
    }

    public function layanans()
    {
        return $this->belongsToMany(JenisLayanan::class, 'tindakan_layanan');
    }
}

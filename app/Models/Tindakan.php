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
        'jenis_layanan_id',
        'catatan'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }

    public function layanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'layanan_id'); // Jika kamu pakai 'layanan_id'
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JanjiTemu extends Model
{
    use HasFactory;

    protected $fillable = ['pasien_id', 'tanggal_janji', 'jam_janji', 'layanan', 'terapis_id'];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }

   public function jenisLayanans()
    {
        return $this->belongsToMany(JenisLayanan::class, 'janji_temu_layanan');
    }

}

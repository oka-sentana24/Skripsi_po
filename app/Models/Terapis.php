<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapis extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'no_telepon'];

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class);
    }

    public function janjiTemu()
    {
        return $this->hasMany(JanjiTemu::class);
    }
}

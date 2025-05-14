<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'queue_number', 'status'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}

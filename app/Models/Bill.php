<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'total_amount', 'payment_status'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}

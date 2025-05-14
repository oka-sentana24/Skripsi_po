<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'registration_date', 'status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    protected static function booted()
    {
        static::created(function ($registration) {
            // Mengecek apakah ada antrean untuk tanggal hari ini
            $todayQueueCount = Queue::whereDate('created_at', Carbon::today())->count();

            // Jika tidak ada antrean untuk hari ini, nomor antrean mulai dari 1
            $queueNumber = $todayQueueCount + 1;

            Queue::create([
                'registration_id' => $registration->id,
                'queue_number' => $queueNumber,
                'status' => 'menunggu', // Default status
            ]);
        });
    }
    
}

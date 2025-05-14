<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['report_type', 'start_date', 'end_date', 'generated_by', 'file_path'];

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}

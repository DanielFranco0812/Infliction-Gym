<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'day',
        'time_from',
        'time_to',
        'class_name',
        'location',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}

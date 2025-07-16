<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'specialist_id',
        'appointment_date',
        'appointment_time',
        'status',
    ];

    protected $casts = [
        // 'appointment_date' => 'date',
        // 'appointment_time' => 'time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }
}

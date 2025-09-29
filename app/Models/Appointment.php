<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, MorphOne};

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'specialist_id',
        'total_value',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'status'      => 'string',
        'total_value' => 'decimal:2',
        'notes'       => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function room(): HasOne
    {
        return $this->hasOne(Room::class);
    }
}

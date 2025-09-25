<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    protected $fillable = ['code', 'status', 'created_by', 'closed_at', 'appointment_id'];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function scopeOpen($q)  { return $q->where('status', 'open'); }
    public function scopeClosed($q){ return $q->where('status', 'closed'); }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}

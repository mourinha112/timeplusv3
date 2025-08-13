<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Payment extends Model
{
    protected $fillable = [
        'gateway_payment_id',
        'user_id',
        'appointment_id',
        'value',
        'billing_type',
        'status',
        'due_date',
        'description',
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'billing_type' => 'string',
        'status'       => 'string',
        'due_date'     => 'date',
        'description'  => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}

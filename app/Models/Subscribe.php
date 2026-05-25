<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'plan_id',
        'gateway_subscription_id',
        'billing_status',
        'next_billing_date',
        'start_date',
        'end_date',
        'cancelled_date',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'cancelled_date'    => 'date',
        'next_billing_date' => 'date',
    ];

    public function isActive(): bool
    {
        return $this->billing_status === self::STATUS_ACTIVE
            && $this->end_date
            && $this->end_date->isFuture()
            && $this->hasConfirmedPayment();
    }

    public function hasConfirmedPayment(): bool
    {
        if ($this->relationLoaded('payments')) {
            return $this->payments->contains(fn (Payment $payment) => $payment->status === 'paid');
        }

        return $this->payments()->where('status', 'paid')->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable')->latest();
    }
}

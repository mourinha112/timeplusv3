<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, MorphOne};

class Subscribe extends Model
{
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_PAUSED    = 'paused';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED   = 'expired';

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

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /** @return MorphMany<Payment, $this> */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /** @return MorphOne<Payment, $this> */
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable')->latest();
    }
}

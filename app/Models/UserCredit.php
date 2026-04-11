<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphTo};

class UserCredit extends Model
{
    public const SOURCE_CANCELLATION = 'cancellation';
    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_REFUND = 'refund';
    public const SOURCE_PROMOTION = 'promotion';

    protected $fillable = [
        'user_id',
        'amount',
        'amount_remaining',
        'source',
        'source_reference_type',
        'source_reference_id',
        'expires_at',
        'consumed_at',
        'description',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'amount_remaining' => 'decimal:2',
        'expires_at'       => 'datetime',
        'consumed_at'      => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sourceReference(): MorphTo
    {
        return $this->morphTo('source_reference');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(UserCreditUsage::class);
    }

    public function isUsable(): bool
    {
        if ($this->amount_remaining <= 0) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}

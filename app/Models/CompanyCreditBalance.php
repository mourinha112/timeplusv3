<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class CompanyCreditBalance extends Model
{
    public const BUCKET_MONTHLY = 'monthly';

    public const BUCKET_EXTRA = 'extra';

    protected $fillable = [
        'company_id',
        'company_plan_id',
        'bucket',
        'credits_total',
        'credits_remaining',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'credits_total'     => 'integer',
        'credits_remaining' => 'integer',
        'valid_from'        => 'date',
        'valid_until'       => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companyPlan(): BelongsTo
    {
        return $this->belongsTo(CompanyPlan::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CompanyCreditUsage::class);
    }

    /** Buckets com saldo e dentro da validade. */
    public function scopeUsable(Builder $query): Builder
    {
        return $query->where('credits_remaining', '>', 0)
            ->whereDate('valid_from', '<=', now())
            ->whereDate('valid_until', '>=', now());
    }

    public function isUsable(): bool
    {
        return $this->credits_remaining > 0
            && $this->valid_from->startOfDay()->lte(now())
            && $this->valid_until->endOfDay()->gte(now());
    }
}

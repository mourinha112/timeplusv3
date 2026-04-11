<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public const CYCLE_MONTHLY = 'monthly';
    public const CYCLE_QUARTERLY = 'quarterly';
    public const CYCLE_YEARLY = 'yearly';
    public const CYCLE_ONE_TIME = 'one_time';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'discount_percentage',
        'max_sessions',
        'billing_cycle',
        'gateway_plan_id',
    ];

    protected $casts = [
        'price'               => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function isRecurring(): bool
    {
        return ($this->billing_cycle ?? self::CYCLE_MONTHLY) !== self::CYCLE_ONE_TIME;
    }

    public function hasDiscount(): bool
    {
        return (float) $this->discount_percentage > 0;
    }

    public function getPriceWithDiscountAttribute(): float
    {
        $price = (float) $this->price;

        if (!$this->hasDiscount()) {
            return round($price, 2);
        }

        $discount = $price * ((float) $this->discount_percentage / 100);

        return round($price - $discount, 2);
    }

    public function getDiscountAmountAttribute(): float
    {
        if (!$this->hasDiscount()) {
            return 0.0;
        }

        return round((float) $this->price - $this->price_with_discount, 2);
    }

    public function getDiscountPercentageFormattedAttribute(): string
    {
        $percentage = (float) $this->discount_percentage;

        if ($percentage === 0.0) {
            return '0';
        }

        $formatted = number_format($percentage, 2, ',', '.');

        return rtrim(rtrim($formatted, '0'), ',');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'gateway_order_id',
        'gateway_charge_id',
        'payable_type',
        'payable_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'expires_at',
        'currency',
        'description',
        'metadata',
        'gateway_payload',
        'refunded_amount',
        'refunded_at',
        'refund_reason',
        'company_id',
        'original_amount',
        'discount_value',
        'discount_percentage',
        'discount',
        'company_plan_name',
        'pix_key',
        'pix_qr_code',
    ];

    protected $casts = [
        'metadata'            => 'array',
        'gateway_payload'     => 'array',
        'paid_at'             => 'datetime',
        'expires_at'          => 'datetime',
        'refunded_at'         => 'datetime',
        'amount'              => 'decimal:2',
        'refunded_amount'     => 'decimal:2',
        'original_amount'     => 'decimal:2',
        'discount_value'      => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount'            => 'decimal:2',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function hasCompanyDiscount(): bool
    {
        return $this->company_id !== null && $this->discount_value > 0;
    }

    public function getDiscountInfo(): array
    {
        if (!$this->hasCompanyDiscount()) {
            return [
                'has_discount'        => false,
                'original_amount'     => $this->amount,
                'employee_paid'       => $this->amount,
                'company_paid'        => 0,
                'discount_percentage' => 0,
            ];
        }

        return [
            'has_discount'        => true,
            'original_amount'     => $this->original_amount,
            'employee_paid'       => $this->amount,
            'company_paid'        => $this->discount_value,
            'discount_percentage' => $this->discount_percentage,
            'company_name'        => $this->company->name ?? 'N/A',
            'plan_name'           => $this->company_plan_name,
        ];
    }
}

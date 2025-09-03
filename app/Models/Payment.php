<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'metadata'        => 'array',
        'gateway_payload' => 'array',
        'paid_at'         => 'datetime',
        'expires_at'      => 'datetime',
        'refunded_at'     => 'datetime',
        'amount'          => 'decimal:2',
        'refunded_amount' => 'decimal:2',
    ];

    public function payable()
    {
        return $this->morphTo();
    }
}

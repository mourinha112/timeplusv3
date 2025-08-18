<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'gateway_order_id',
        'gateway_charge_id',
        'payable',
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

    public function payable()
    {
        return $this->morphTo();
    }
}

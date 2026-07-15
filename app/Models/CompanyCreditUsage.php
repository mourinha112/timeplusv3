<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyCreditUsage extends Model
{
    protected $fillable = [
        'company_credit_balance_id',
        'appointment_id',
        'user_id',
        'credits_used',
    ];

    protected $casts = [
        'credits_used' => 'integer',
    ];

    public function balance(): BelongsTo
    {
        return $this->belongsTo(CompanyCreditBalance::class, 'company_credit_balance_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

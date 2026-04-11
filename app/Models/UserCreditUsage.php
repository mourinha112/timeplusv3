<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCreditUsage extends Model
{
    protected $fillable = [
        'user_credit_id',
        'appointment_id',
        'amount_used',
    ];

    protected $casts = [
        'amount_used' => 'decimal:2',
    ];

    public function userCredit(): BelongsTo
    {
        return $this->belongsTo(UserCredit::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}

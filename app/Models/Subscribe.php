<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'cancelled_date',
    ];

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
}

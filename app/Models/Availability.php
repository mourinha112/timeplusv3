<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'specialist_id',
        'available_date',
        'available_time',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }
}

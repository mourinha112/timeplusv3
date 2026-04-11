<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    public const MODE_TIMEPLUS = 'timeplus';
    public const MODE_PARTICULAR = 'particular';
    public const MODE_BOTH = 'both';

    protected $fillable = [
        'specialist_id',
        'available_date',
        'available_time',
        'service_mode',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function acceptsMode(string $mode): bool
    {
        return $this->service_mode === self::MODE_BOTH || $this->service_mode === $mode;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $fillable = ['name'];

    public function specialists()
    {
        return $this->belongsToMany(Specialist::class, 'reason_specialists')->withTimestamps();
    }
}

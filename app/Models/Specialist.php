<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Specialist extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'cpf',
        'phone_number',
        'email',
        'password',
        'year_started_acting',
        'crp',
        'gender_id',
        'specialty_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function specialty()
    {
        return $this->belongsToMany(Specialty::class);
    }
}

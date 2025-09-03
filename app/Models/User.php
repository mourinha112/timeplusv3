<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'gateway_customer_id',
        'avatar',
        'name',
        'cpf',
        'phone_number',
        'birth_date',
        'email',
        'password',
        'is_active',
        'email_verified_at',
        'remember_token',
        'recovery_password_token',
        'recovery_password_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'recovery_password_token',
        'recovery_password_token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function setBirthDateAttribute($value)
    {
        if ($value) {
            // Converte DD/MM/AAAA para AAAA-MM-DD
            $date                           = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
            $this->attributes['birth_date'] = $date->format('Y-m-d');
        }
    }

    public function getBirthDateAttribute($value)
    {
        if ($value) {
            // Converte AAAA-MM-DD para DD/MM/AAAA para exibição
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        }

        return $value;
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}

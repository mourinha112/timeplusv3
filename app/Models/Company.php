<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, MorphMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'cnpj',
        'email',
        'gateway_customer_id',
        'contact_name',
        'contact_role',
        'contact_phone',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'is_active',
        'recovery_password_token',
        'recovery_password_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'recovery_password_token',
    ];

    protected $casts = [
        'is_active'                          => 'boolean',
        'password'                           => 'hashed',
        'recovery_password_token_expires_at' => 'datetime',
    ];

    public function companyPlans(): HasMany
    {
        return $this->hasMany(CompanyPlan::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot(['company_plan_id', 'department', 'is_active'])
            ->withTimestamps();
    }

    public function activeEmployees(): BelongsToMany
    {
        return $this->employees()->wherePivot('is_active', true);
    }

    public function creditBalances(): HasMany
    {
        return $this->hasMany(CompanyCreditBalance::class);
    }
}

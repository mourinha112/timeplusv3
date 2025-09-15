<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough};

class CompanyPlan extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'is_active'           => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function activeCompanyUsers(): HasMany
    {
        return $this->companyUsers()->where('is_active', true);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            CompanyUser::class,
            'company_plan_id',
            'id',
            'id',
            'user_id'
        )->where('company_user.is_active', true);
    }

    // Método para contar quantos funcionários estão usando este plano
    public function getActiveUsersCount(): int
    {
        return $this->activeCompanyUsers()->count();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class CompanyUser extends Model
{
    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'company_plan_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyPlan(): BelongsTo
    {
        return $this->belongsTo(CompanyPlan::class);
    }

    // Método para verificar se o funcionário tem um plano ativo
    public function hasActivePlan(): bool
    {
        return $this->is_active && $this->company_plan_id !== null && $this->companyPlan?->is_active;
    }

    // Método para obter o desconto aplicável
    public function getDiscountPercentage(): float
    {
        return $this->hasActivePlan() ? $this->companyPlan->discount_percentage : 0;
    }
}

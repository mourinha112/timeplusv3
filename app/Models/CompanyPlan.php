<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough};

class CompanyPlan extends Model
{
    public const BILLING_PER_EMPLOYEE = 'per_employee';

    public const BILLING_CREDIT_PACK = 'credit_pack';

    /** Valor por sessão/crédito no modelo de pacote de créditos. */
    public const CREDIT_UNIT_PRICE = 30.00;

    /** Por funcionário ativo: 1º mês (valor fica com o corretor). */
    public const PER_EMPLOYEE_FIRST_MONTH_PRICE = 30.00;

    /** Por funcionário ativo: meses seguintes (valor fica com a plataforma). */
    public const PER_EMPLOYEE_RECURRING_PRICE = 60.00;

    protected $fillable = [
        'company_id',
        'name',
        'discount_percentage',
        'billing_model',
        'monthly_credits',
        'credits_per_employee',
        'price_per_unit',
        'gateway_subscription_id',
        'billing_status',
        'next_billing_date',
        'contracted_at',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage'  => 'decimal:2',
        'monthly_credits'      => 'integer',
        'credits_per_employee' => 'integer',
        'price_per_unit'       => 'decimal:2',
        'next_billing_date'    => 'date',
        'contracted_at'        => 'date',
        'is_active'            => 'boolean',
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

    public function creditBalances(): HasMany
    {
        return $this->hasMany(CompanyCreditBalance::class);
    }

    // Método para contar quantos funcionários estão usando este plano
    public function getActiveUsersCount(): int
    {
        return $this->activeCompanyUsers()->count();
    }

    public function isCreditPack(): bool
    {
        return $this->billing_model === self::BILLING_CREDIT_PACK;
    }

    public function isPerEmployee(): bool
    {
        return $this->billing_model === self::BILLING_PER_EMPLOYEE;
    }

    /**
     * O 1º mês de contrato (R$30/funcionário, repasse do corretor) vale até
     * completar 1 mês da contratação.
     */
    public function isInFirstMonth(): bool
    {
        $contractedAt = $this->contracted_at ?? $this->created_at;

        return $contractedAt !== null && now()->lt($contractedAt->copy()->addMonth());
    }

    /** Preço por funcionário ativo no ciclo atual (R$30 no 1º mês, R$60 depois). */
    public function currentPerEmployeePrice(): float
    {
        return $this->isInFirstMonth()
            ? self::PER_EMPLOYEE_FIRST_MONTH_PRICE
            : self::PER_EMPLOYEE_RECURRING_PRICE;
    }
}

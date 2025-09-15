<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }

    public function activeCompanies(): BelongsToMany
    {
        return $this->companies()->wherePivot('is_active', true);
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function activeCompanyPlans()
    {
        return CompanyUser::where('user_id', $this->id)
            ->where('is_active', true)
            ->whereNotNull('company_plan_id')
            ->whereHas('companyPlan', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['companyPlan.company']);
    }

    public function hasActiveCompanyPlan(): bool
    {
        return $this->activeCompanyPlans()->exists();
    }

    public function getActiveCompanyPlan()
    {
        return $this->activeCompanyPlans()->first();
    }

    /**
     * Calcula o valor que o funcionário deve pagar considerando desconto da empresa
     */
    public function calculatePaymentAmount($originalPrice): array
    {
        $activeCompanyPlan = $this->getActiveCompanyPlan();

        if (!$activeCompanyPlan) {
            return [
                'employee_amount'      => $originalPrice,
                'company_amount'       => 0,
                'discount_percentage'  => 0,
                'has_company_discount' => false,
            ];
        }

        $discountPercentage = $activeCompanyPlan->companyPlan->discount_percentage;
        $discountAmount     = ($originalPrice * $discountPercentage) / 100;
        $employeeAmount     = $originalPrice - $discountAmount;

        return [
            'employee_amount'      => round($employeeAmount, 2),
            'company_amount'       => round($discountAmount, 2),
            'discount_percentage'  => $discountPercentage,
            'has_company_discount' => true,
            'company_name'         => $activeCompanyPlan->company->name,
            'plan_name'            => $activeCompanyPlan->companyPlan->name,
        ];
    }
}

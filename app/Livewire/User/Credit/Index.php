<?php

namespace App\Livewire\User\Credit;

use App\Models\{CompanyCreditUsage, CompanyPlan, UserCredit};
use App\Services\Credit\{CompanyCreditService, UserCreditService};
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Meus Créditos', 'guard' => 'user'])]
class Index extends Component
{
    /** Vínculo ativo com plano da empresa (CompanyUser), se houver. */
    #[Computed]
    public function companyUser()
    {
        return Auth::user()->getActiveCompanyPlan();
    }

    /**
     * Situação dos créditos do plano da empresa (modelo pacote de créditos):
     * limite mensal universal, usados, restantes e custo por sessão.
     */
    #[Computed]
    public function companyCreditInfo(): ?array
    {
        $companyUser = $this->companyUser;

        if (!$companyUser || !$companyUser->companyPlan?->isCreditPack()) {
            return null;
        }

        $service = app(CompanyCreditService::class);
        $company = $companyUser->company;
        $plan    = $companyUser->companyPlan;

        $used  = $service->usedByUserInMonth($company, Auth::user());
        $limit = $plan->credits_per_employee;

        return [
            'company_name'       => $company->name,
            'plan_name'          => $plan->name,
            'limit'              => $limit,
            'used'               => $used,
            'employee_remaining' => $limit !== null ? max(0, $limit - $used) : null,
            'company_balance'    => $service->balance($company),
            'unit_price'         => CompanyPlan::CREDIT_UNIT_PRICE,
        ];
    }

    /** Histórico de sessões pagas com crédito da empresa. */
    #[Computed]
    public function companyUsages()
    {
        return CompanyCreditUsage::query()
            ->where('user_id', Auth::id())
            ->with(['appointment.specialist', 'balance'])
            ->latest()
            ->limit(20)
            ->get();
    }

    /** Saldo monetário pessoal (créditos de cancelamento etc.). */
    #[Computed]
    public function personalBalance(): float
    {
        return app(UserCreditService::class)->balance(Auth::user());
    }

    /** Créditos pessoais com saldo, para exibir validade. */
    #[Computed]
    public function personalCredits()
    {
        return UserCredit::query()
            ->where('user_id', Auth::id())
            ->where('amount_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->orderBy('expires_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.credit.index');
    }
}

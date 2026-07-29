<?php

namespace App\Livewire\Master\CompanyPlan;

use App\Models\Company;
use App\Models\CompanyPlan;
use App\Services\Credit\CompanyCreditService;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Criar Plano da Empresa', 'guard' => 'master'])]
class Create extends Component
{
    public Company $company;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|in:per_employee,credit_pack')]
    public string $billing_model = 'per_employee';

    #[Rule('required_if:billing_model,credit_pack|integer|min:1')]
    public $monthly_credits = 1;

    /** Limite universal de créditos por funcionário/mês (vazio = sem limite). */
    #[Rule('nullable|integer|min:1')]
    public $credits_per_employee = null;

    #[Rule('required|numeric|min:30')]
    public $price_per_unit = 30.00;

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->updatedBillingModel($this->billing_model);
    }

    public function updatedBillingModel(string $value): void
    {
        $this->price_per_unit = $value === 'credit_pack'
            ? CompanyPlan::CREDIT_UNIT_PRICE
            : CompanyPlan::PER_EMPLOYEE_FIRST_MONTH_PRICE;
    }

    public function save()
    {
        $this->validate();

        $isCreditPack = $this->billing_model === 'credit_pack';

        $plan = CompanyPlan::create([
            'company_id'           => $this->company->id,
            'name'                 => $this->name,
            'discount_percentage'  => 0,
            'billing_model'        => $this->billing_model,
            'monthly_credits'      => $isCreditPack ? $this->monthly_credits : 0,
            'credits_per_employee' => $isCreditPack ? ($this->credits_per_employee ?: null) : null,
            'price_per_unit'       => $isCreditPack
                ? CompanyPlan::CREDIT_UNIT_PRICE
                : CompanyPlan::PER_EMPLOYEE_FIRST_MONTH_PRICE,
            'billing_status' => 'active',
            'contracted_at'  => now()->toDateString(),
            'is_active'      => true,
        ]);

        /* Libera os créditos do mês corrente imediatamente */
        if ($isCreditPack) {
            app(CompanyCreditService::class)->grantMonthly($plan);
        }

        session()->flash('message', 'Plano criado com sucesso!');

        return $this->redirect(route('master.company.show', ['company' => $this->company->id]));
    }

    public function render()
    {
        return view('livewire.master.company-plan.create');
    }
}

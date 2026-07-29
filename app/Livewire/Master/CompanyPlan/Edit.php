<?php

namespace App\Livewire\Master\CompanyPlan;

use App\Models\CompanyPlan;
use App\Services\Credit\CompanyCreditService;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Plano da Empresa', 'guard' => 'master'])]
class Edit extends Component
{
    public $plan = null;

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
    public $price_per_unit = 60.00;

    public function mount($plan)
    {
        $this->plan = CompanyPlan::with('company')->findOrFail($plan);

        $this->name                 = $this->plan->name;
        $this->billing_model        = $this->plan->billing_model ?? 'per_employee';
        $this->monthly_credits      = (int) ($this->plan->monthly_credits ?: 1);
        $this->credits_per_employee = $this->plan->credits_per_employee;
        $this->price_per_unit       = (float) ($this->plan->price_per_unit ?: 60.00);
    }

    public function updatedBillingModel(string $value): void
    {
        $this->price_per_unit = $value === 'credit_pack'
            ? CompanyPlan::CREDIT_UNIT_PRICE
            : $this->plan->currentPerEmployeePrice();
    }

    public function save()
    {
        $this->validate();

        $isCreditPack = $this->billing_model === 'credit_pack';

        $this->plan->update([
            'name'                 => $this->name,
            'discount_percentage'  => 0,
            'billing_model'        => $this->billing_model,
            'monthly_credits'      => $isCreditPack ? $this->monthly_credits : 0,
            'credits_per_employee' => $isCreditPack ? ($this->credits_per_employee ?: null) : null,
            'price_per_unit'       => $isCreditPack
                ? CompanyPlan::CREDIT_UNIT_PRICE
                : $this->plan->currentPerEmployeePrice(),
            'contracted_at' => $this->plan->contracted_at ?? $this->plan->created_at?->toDateString() ?? now()->toDateString(),
        ]);

        /* Ajusta o bucket mensal do mês corrente à nova quantidade de créditos */
        if ($isCreditPack) {
            app(CompanyCreditService::class)->grantMonthly($this->plan->fresh());
        }

        session()->flash('message', 'Plano atualizado com sucesso!');

        return $this->redirect(route('master.company.show', ['company' => $this->plan->company_id]));
    }

    public function render()
    {
        return view('livewire.master.company-plan.edit');
    }
}

<?php

namespace App\Livewire\Master\CompanyPlan;

use App\Models\Company;
use App\Models\CompanyPlan;
use App\Services\Billing\CompanyBillingService;
use App\Services\Credit\CompanyCreditService;
use Illuminate\Support\Facades\Log;
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

        /* A empresa opta por UM modelo: não pode ter dois planos ativos ao mesmo tempo */
        $activePlan = $this->company->companyPlans()->where('is_active', true)->first();

        if ($activePlan) {
            $this->addError('billing_model', "A empresa já possui o plano ativo \"{$activePlan->name}\" ("
                . ($activePlan->isCreditPack() ? 'pacote de créditos' : 'por funcionário')
                . '). Desative-o antes de criar um novo plano.');

            return;
        }

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

        /* Gera a 1ª fatura PIX do plano (empresa paga em empresa/pagamentos ou empresa/creditos) */
        try {
            $invoice = app(CompanyBillingService::class)->generateMonthlyCharge($plan);

            session()->flash('message', $invoice
                ? 'Plano criado com sucesso! A fatura PIX do 1º mês foi gerada para a empresa.'
                : 'Plano criado com sucesso! A fatura será gerada quando houver funcionários ativos no plano (cobrança automática do dia 1º).');
        } catch (\Exception $e) {
            Log::warning('Plano criado, mas falhou ao gerar a 1ª fatura PIX', [
                'company_plan_id' => $plan->id,
                'message'         => $e->getMessage(),
            ]);
            session()->flash('message', 'Plano criado, mas não foi possível gerar a fatura PIX do 1º mês (verifique CNPJ/e-mail da empresa e as chaves Asaas). Ela será gerada na cobrança automática do dia 1º.');
        }

        return $this->redirect(route('master.company.show', ['company' => $this->company->id]));
    }

    public function render()
    {
        return view('livewire.master.company-plan.create');
    }
}

<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use App\Models\CompanyPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes da Empresa', 'guard' => 'master'])]
class Show extends Component
{
    public Company $company;

    public function mount(Company $company): void
    {
        $this->company = $company->load(['companyPlans', 'payments', 'employees']);
    }

    public function togglePlanStatus(int $planId): void
    {
        $plan = CompanyPlan::where('company_id', $this->company->id)->findOrFail($planId);

        /* A empresa opta por UM modelo: bloqueia ativar um 2º plano simultâneo */
        if (!$plan->is_active) {
            $otherActive = CompanyPlan::where('company_id', $this->company->id)
                ->where('id', '!=', $plan->id)
                ->where('is_active', true)
                ->first();

            if ($otherActive) {
                session()->flash('error', "A empresa já possui o plano ativo \"{$otherActive->name}\". Desative-o antes de ativar outro plano.");

                return;
            }
        }

        $plan->update(['is_active' => !$plan->is_active]);

        $this->company->load('companyPlans');

        session()->flash('message', 'Plano ' . ($plan->is_active ? 'ativado' : 'desativado') . ' com sucesso!');
    }

    public function deletePlan(int $planId): void
    {
        $plan = CompanyPlan::where('company_id', $this->company->id)->findOrFail($planId);

        if ($plan->getActiveUsersCount() > 0) {
            session()->flash('error', 'Não é possível excluir um plano que possui funcionários ativos!');

            return;
        }

        $plan->delete();

        $this->company->load('companyPlans');

        session()->flash('message', 'Plano excluído com sucesso!');
    }

    public function render()
    {
        return view('livewire.master.company.show');
    }
}

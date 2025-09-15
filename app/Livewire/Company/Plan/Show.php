<?php

namespace App\Livewire\Company\Plan;

use App\Models\CompanyPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Plano', 'guard' => 'company'])]
class Show extends Component
{
  public CompanyPlan $plan;

  public function mount(CompanyPlan $plan): void
  {
    $company = Auth::guard('company')->user();

    // Verificar se o plano pertence à empresa logada
    if ($plan->company_id !== $company->id) {
      abort(403, 'Acesso negado.');
    }

    $this->plan = $plan->load(['company', 'companyUsers.user']);
  }

  public function render()
  {
    return view('livewire.company.plan.show');
  }
}

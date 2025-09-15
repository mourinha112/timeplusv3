<?php

namespace App\Livewire\Company\Plan;

use App\Models\CompanyPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Plano', 'guard' => 'company'])]
class Edit extends Component
{
  public $company = null;
  public $plan = null;

  #[Rule('required|string|max:255')]
  public $name = '';

  #[Rule('required|numeric|min:0.01|max:100')]
  public $discount_percentage = '';

  public function mount($plan)
  {
    $this->company = Auth::guard('company')->user();

    // Verificar se o plano pertence à empresa
    $this->plan = CompanyPlan::where('id', $plan)
      ->where('company_id', $this->company->id)
      ->firstOrFail();

    // Preencher os campos com os dados atuais
    $this->name = $this->plan->name;
    $this->discount_percentage = $this->plan->discount_percentage;
  }

  public function save()
  {
    $this->validate();

    $this->plan->update([
      'name' => $this->name,
      'discount_percentage' => $this->discount_percentage,
    ]);

    session()->flash('success', 'Plano atualizado com sucesso!');

    return $this->redirect(route('company.plan.index'));
  }

  public function render()
  {
    return view('livewire.company.plan.edit');
  }
}

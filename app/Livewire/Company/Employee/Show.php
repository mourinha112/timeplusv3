<?php

namespace App\Livewire\Company\Employee;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Funcionário', 'guard' => 'company'])]
class Show extends Component
{
  public User $employee;
  public CompanyUser $companyUser;

  public function mount(User $employee): void
  {
    $company = Auth::guard('company')->user();

    // Verificar se o funcionário pertence à empresa logada
    $this->companyUser = CompanyUser::where('company_id', $company->id)
      ->where('user_id', $employee->id)
      ->with(['companyPlan'])
      ->firstOrFail();

    $this->employee = $employee;
  }

  public function render()
  {
    return view('livewire.company.employee.show');
  }
}

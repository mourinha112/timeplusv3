<?php

namespace App\Livewire\Company\Employee;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Funcionário', 'guard' => 'company'])]
class Edit extends Component
{
  public $company = null;
  public $employee = null;
  public $companyUser = null;

  #[Rule('required|string|max:255')]
  public $name = '';

  #[Rule('required|string|size:14')]
  public $cpf = '';

  #[Rule('required|string|max:20')]
  public $phone_number = '';

  #[Rule('required|date_format:d/m/Y')]
  public $birth_date = '';

  #[Rule('required|exists:company_plans,id')]
  public $company_plan_id = null;

  public function mount($employee)
  {
    $this->company = Auth::guard('company')->user();

    // Verificar se o funcionário pertence à empresa
    $this->companyUser = CompanyUser::where('company_id', $this->company->id)
      ->where('user_id', $employee)
      ->firstOrFail();

    $this->employee = User::findOrFail($employee);

    // Preencher os campos com os dados atuais
    $this->name = $this->employee->name;
    $this->cpf = $this->employee->cpf;
    $this->phone_number = $this->employee->phone_number;
    $this->birth_date = $this->employee->birth_date;
    $this->company_plan_id = $this->companyUser->company_plan_id;
  }

  public function save()
  {
    $this->validate();

    // Atualizar dados do usuário
    $this->employee->update([
      'name' => $this->name,
      'cpf' => $this->cpf,
      'phone_number' => $this->phone_number,
      'birth_date' => $this->birth_date,
    ]);

    // Atualizar plano do funcionário
    $this->companyUser->update([
      'company_plan_id' => $this->company_plan_id,
    ]);

    session()->flash('message', 'Funcionário atualizado com sucesso!');

    return redirect()->route('company.employee.index');
  }
  public function render()
  {
    $companyPlans = $this->company->companyPlans()->get();

    return view('livewire.company.employee.edit', [
      'companyPlans' => $companyPlans
    ]);
  }
}

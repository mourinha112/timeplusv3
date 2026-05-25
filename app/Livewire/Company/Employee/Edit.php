<?php

namespace App\Livewire\Company\Employee;

use App\Facades\Asaas;
use App\Models\{CompanyUser, User};
use App\Rules\{FormattedCpf, ValidatedCpf};
use Illuminate\Support\Facades\{Auth, DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Locked, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Funcionário', 'guard' => 'company'])]
class Edit extends Component
{
    public $company = null;

    public $employee = null;

    public $companyUser = null;

    #[Locked]
    public int $employeeId;

    #[Rule('required|string|max:255')]
    public $name = '';

    public $cpf = '';

    #[Rule('required|string|max:20')]
    public $phone_number = '';

    #[Rule('required|date_format:d/m/Y')]
    public $birth_date = '';

    #[Rule('nullable|exists:company_plans,id')]
    public $company_plan_id = null;

    #[Rule('nullable|string|max:120')]
    public ?string $department = null;

    public function mount($employee)
    {
        $this->company = Auth::guard('company')->user();

        $this->companyUser = CompanyUser::where('company_id', $this->company->id)
            ->where('user_id', $employee)
            ->firstOrFail();

        $this->employee   = User::findOrFail($employee);
        $this->employeeId = $this->employee->id;

        $this->name            = $this->employee->name;
        $this->cpf             = $this->employee->cpf;
        $this->phone_number    = $this->employee->phone_number;
        $this->birth_date      = $this->employee->birth_date;
        $this->company_plan_id = $this->companyUser->company_plan_id;
        $this->department      = $this->companyUser->department;
    }

    public function save()
    {
        $this->validate([
            'name'            => ['required', 'string', 'max:255'],
            'cpf'             => ['required', 'string', 'size:14', new FormattedCpf(), new ValidatedCpf(), \Illuminate\Validation\Rule::unique('users', 'cpf')->ignore($this->employeeId)],
            'phone_number'    => ['required', 'string', 'max:20'],
            'birth_date'      => ['required', 'date_format:d/m/Y'],
            'company_plan_id' => ['nullable', 'exists:company_plans,id'],
            'department'      => ['nullable', 'string', 'max:120'],
        ]);

        try {
            DB::beginTransaction();

            $this->employee->update([
                'name'         => $this->name,
                'cpf'          => $this->cpf,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
            ]);

            if (!$this->employee->gateway_customer_id) {
                $gateway = Asaas::customer()->create([
                    'code'         => $this->employee->id,
                    'name'         => $this->employee->name,
                    'email'        => $this->employee->email,
                    'document'     => $this->employee->cpf,
                    'mobile_phone' => $this->employee->phone_number,
                ]);

                $this->employee->update(['gateway_customer_id' => $gateway['id']]);
            }

            $this->companyUser->update([
                'company_plan_id' => $this->company_plan_id,
                'department'      => $this->department,
            ]);

            DB::commit();

            session()->flash('message', 'Funcionário atualizado com sucesso!');

            return redirect()->route('company.employee.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro interno::' . get_class($this), [
                'message'     => $e->getMessage(),
                'employee_id' => $this->employee->id,
                'company_id'  => $this->company->id,
                'ip'          => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar o funcionário.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        $companyPlans = $this->company->companyPlans()->get();

        return view('livewire.company.employee.edit', [
            'companyPlans' => $companyPlans,
        ]);
    }
}

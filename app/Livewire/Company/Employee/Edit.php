<?php

namespace App\Livewire\Company\Employee;

use App\Facades\Asaas;
use App\Models\{CompanyUser, User};
use App\Notifications\EmployeeCredentialsNotification;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Str;
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

    #[Rule('required|string|size:14')]
    public $cpf = '';

    #[Rule('required|string|max:20')]
    public $phone_number = '';

    #[Rule('required|date_format:d/m/Y')]
    public $birth_date = '';

    #[Rule('required|exists:company_plans,id')]
    public $company_plan_id = null;

    // Campos para alteração de senha
    public bool $showPasswordModal = false;

    public string $newPassword = '';

    public bool $sendPasswordEmail = true;

    public function mount($employee)
    {
        $this->company = Auth::guard('company')->user();

        // Verificar se o funcionário pertence à empresa
        $this->companyUser = CompanyUser::where('company_id', $this->company->id)
            ->where('user_id', $employee)
            ->firstOrFail();

        $this->employee   = User::findOrFail($employee);
        $this->employeeId = $this->employee->id;

        // Preencher os campos com os dados atuais
        $this->name            = $this->employee->name;
        $this->cpf             = $this->employee->cpf;
        $this->phone_number    = $this->employee->phone_number;
        $this->birth_date      = $this->employee->birth_date;
        $this->company_plan_id = $this->companyUser->company_plan_id;
    }

    public function openPasswordModal(): void
    {
        $this->newPassword       = Str::random(12);
        $this->sendPasswordEmail = true;
        $this->showPasswordModal = true;
    }

    public function closePasswordModal(): void
    {
        $this->showPasswordModal = false;
        $this->newPassword       = '';
    }

    public function generateNewPassword(): void
    {
        $this->newPassword = Str::random(12);
    }

    public function resetPassword(): void
    {
        try {
            $employee = User::findOrFail($this->employeeId);

            $employee->update([
                'password' => bcrypt($this->newPassword),
            ]);

            // Enviar email com nova senha se solicitado
            if ($this->sendPasswordEmail) {
                $employee->notify(new EmployeeCredentialsNotification(
                    companyName: $this->company->name,
                    email: $employee->email,
                    password: $this->newPassword
                ));
            }

            $this->showPasswordModal = false;
            $this->newPassword       = '';

            LivewireAlert::title('Sucesso!')
                ->text('Senha alterada com sucesso!' . ($this->sendPasswordEmail ? ' O funcionário receberá um e-mail com as novas credenciais.' : ''))
                ->success()
                ->show();

        } catch (\Exception $e) {
            Log::error('Erro ao resetar senha::' . get_class($this), [
                'message'     => $e->getMessage(),
                'employee_id' => $this->employeeId,
                'ip'          => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar alterar a senha.')
                ->error()
                ->show();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Atualizar dados do usuário
            $this->employee->update([
                'name'         => $this->name,
                'cpf'          => $this->cpf,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
            ]);

            // Verificar se o funcionário tem gateway_customer_id, se não, criar
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

            // Atualizar plano do funcionário
            $this->companyUser->update([
                'company_plan_id' => $this->company_plan_id,
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

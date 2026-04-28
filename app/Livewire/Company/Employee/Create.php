<?php

namespace App\Livewire\Company\Employee;

use App\Facades\Asaas;
use App\Models\Company;
use App\Models\User;
use App\Notifications\EmployeeCredentialsNotification;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Locked, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Adicionar Funcionário', 'guard' => 'company'])]
class Create extends Component
{
    #[Locked]
    public int $companyId;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|size:14')]
    public string $cpf = '';

    #[Rule('required|string|max:20')]
    public string $phone_number = '';

    #[Rule('required|date_format:d/m/Y')]
    public string $birth_date = '';

    #[Rule('required|email|max:255|unique:users,email')]
    public string $email = '';

    #[Rule('nullable|exists:company_plans,id')]
    public $company_plan_id = null;

    public function mount(): void
    {
        $this->companyId = Auth::guard('company')->id();

        $defaultPlan = Company::find($this->companyId)
            ?->companyPlans()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if ($defaultPlan) {
            $this->company_plan_id = $defaultPlan->id;
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $company = Company::findOrFail($this->companyId);

            $password = Str::random(12);

            $user = User::create([
                'name'         => $this->name,
                'cpf'          => $this->cpf,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
                'email'        => $this->email,
                'password'     => $password,
                'is_active'    => true,
            ]);

            try {
                $gateway = Asaas::customer()->create([
                    'code'         => $user->id,
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'document'     => $user->cpf,
                    'mobile_phone' => $user->phone_number,
                ]);

                $user->update(['gateway_customer_id' => $gateway['id']]);
            } catch (\Exception $asaasError) {
                Log::warning('Erro ao criar customer Asaas no cadastro de funcionário', [
                    'user_id' => $user->id,
                    'error'   => $asaasError->getMessage(),
                ]);
            }

            if (!$company->employees()->where('user_id', $user->id)->exists()) {
                $company->employees()->attach($user->id, [
                    'is_active'       => true,
                    'company_plan_id' => $this->company_plan_id,
                ]);
            }

            DB::commit();

            try {
                $user->notify(new EmployeeCredentialsNotification(
                    companyName: $company->name,
                    email: $user->email,
                    password: $password
                ));
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de credenciais', [
                    'user_id' => $user->id,
                    'error'   => $emailError->getMessage(),
                ]);
            }

            session()->flash('message', 'Funcionário cadastrado! Um e-mail com as credenciais foi enviado para ' . $user->email);

            $this->redirect(route('company.employee.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'name'    => $this->name,
                'cpf'     => $this->cpf,
                'company' => $this->companyId,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar criar o funcionário.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        $companyPlans = Company::findOrFail($this->companyId)
            ->companyPlans()
            ->where('is_active', true)
            ->get();

        return view('livewire.company.employee.create', [
            'companyPlans' => $companyPlans,
        ]);
    }
}

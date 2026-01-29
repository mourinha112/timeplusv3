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

    public bool $showCredentialsModal = false;

    public string $userEmail = '';

    public string $userPassword = '';

    public string $userName = '';

    public bool $userExists = false;

    public function mount(): void
    {
        $this->companyId = Auth::guard('company')->id();
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $company = Company::findOrFail($this->companyId);

            // Gerar senha temporária
            $password = Str::random(12);

            // Criar novo usuário com email informado
            $user = User::create([
                'name'         => $this->name,
                'cpf'          => $this->cpf,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
                'email'        => $this->email,
                'password'     => bcrypt($password),
                'is_active'    => true,
            ]);

            // Criar customer no gateway Asaas
            $gateway = Asaas::customer()->create([
                'code'         => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'document'     => $user->cpf,
                'mobile_phone' => $user->phone_number,
            ]);

            $user->update(['gateway_customer_id' => $gateway['id']]);

            // Verificar se já não está vinculado à empresa
            if (!$company->employees()->where('user_id', $user->id)->exists()) {
                // Vincular usuário à empresa
                $company->employees()->attach($user->id, [
                    'is_active' => true,
                ]);
            }

            DB::commit();

            // Guardar dados para o modal (apenas strings simples)
            $this->userExists   = false;
            $this->userEmail    = $user->email;
            $this->userPassword = $password;
            $this->userName     = $user->name;

            // Enviar email com credenciais para o funcionário
            try {
                $user->notify(new EmployeeCredentialsNotification(
                    companyName: $company->name,
                    email: $user->email,
                    password: $password
                ));
                Log::info('Email de credenciais enviado com sucesso', [
                    'user_id'    => $user->id,
                    'user_email' => $user->email,
                    'company'    => $company->name,
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de credenciais', [
                    'user_id'    => $user->id,
                    'user_email' => $user->email,
                    'error'      => $emailError->getMessage(),
                ]);
            }

            // Mostrar modal com informações
            $this->showCredentialsModal = true;

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

    public function closeModal()
    {
        $this->showCredentialsModal = false;

        return $this->redirect(route('company.employee.index'));
    }

    public function render()
    {
        return view('livewire.company.employee.create');
    }
}

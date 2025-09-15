<?php

namespace App\Livewire\Company\Employee;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Adicionar Funcionário', 'guard' => 'company'])]
class Create extends Component
{
    public $company = null;

    public $user = null;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|size:14')]
    public $cpf = '';

    #[Rule('required|string|max:20')]
    public $phone_number = '';

    #[Rule('required|date_format:d/m/Y')]
    public $birth_date = '';

    // Email será gerado automaticamente, não precisa de validação do usuário

    public $showCredentialsModal = false;

    public $userEmail = '';

    public $userPassword = '';

    public $userExists = false;

    public function mount()
    {
        $this->company = Auth::guard('company')->user();
    }

    public function save()
    {
        $this->validate();

        // Gerar email interno fake
        $random         = Str::random(16);
        $generatedEmail = $random . '@timeplus.com.br';

        // Garantir que o email é único
        while (User::where('email', $generatedEmail)->exists()) {
            $random         = Str::random(16);
            $generatedEmail = $random . '@timeplus.com.br';
        }

        // Criar novo usuário com email interno
        $password = Str::random(12);

        $this->user = User::create([
            'name'         => $this->name,
            'cpf'          => $this->cpf,
            'phone_number' => $this->phone_number,
            'birth_date'   => $this->birth_date,
            'email'        => $generatedEmail,
            'password'     => bcrypt($password),
            'is_active'    => true,
        ]);

        $this->userExists   = false;
        $this->userEmail    = $this->user->email;
        $this->userPassword = $password;

        // Verificar se já não está vinculado à empresa
        if (!$this->company->employees()->where('user_id', $this->user->id)->exists()) {
            // Vincular usuário à empresa
            $this->company->employees()->attach($this->user->id, [
                'is_active' => true,
            ]);
        }

        // Mostrar modal com informações
        $this->showCredentialsModal = true;
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

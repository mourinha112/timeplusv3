<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Nova Empresa', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|size:14|unique:companies,cnpj')]
    public $cnpj = '';

    #[Rule('required|email|unique:companies,email')]
    public $email = '';

    #[Rule('required|string|max:15')]
    public $phone = '';

    #[Rule('required|string|max:255')]
    public $address = '';

    #[Rule('required|string|max:255')]
    public $city = '';

    #[Rule('required|string|size:2')]
    public $state = '';

    #[Rule('required|string|max:9')]
    public $zip_code = '';

    #[Rule('boolean')]
    public $is_active = true;

    #[Rule('nullable|string|max:255')]
    public ?string $contact_name = null;

    #[Rule('nullable|string|max:120')]
    public ?string $contact_role = null;

    #[Rule('nullable|string|max:20')]
    public ?string $contact_phone = null;

    // Propriedades para o modal de credenciais
    public $showCredentialsModal = false;

    public $companyEmail = '';

    public $companyPassword = '';

    public function save()
    {
        $this->validate();

        // Gerar senha aleatória
        $password = Str::random(12);

        $company = Company::create([
            'name'          => $this->name,
            'cnpj'          => $this->cnpj,
            'email'         => $this->email,
            'password'      => $password,
            'phone'         => preg_replace('/\D/', '', $this->phone),
            'address'       => $this->address,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => preg_replace('/\D/', '', $this->zip_code),
            'is_active'     => $this->is_active,
            'contact_name'  => $this->contact_name,
            'contact_role'  => $this->contact_role,
            'contact_phone' => $this->contact_phone,
        ]);

        // Armazenar credenciais para exibir no modal
        $this->companyEmail    = $this->email;
        $this->companyPassword = $password;

        // Exibir modal com as credenciais
        $this->showCredentialsModal = true;
    }

    public function closeModal()
    {
        $this->showCredentialsModal = false;
        session()->flash('message', 'Empresa criada com sucesso!');

        return $this->redirect(route('master.company.index'));
    }

    public function render()
    {
        return view('livewire.master.company.create');
    }
}

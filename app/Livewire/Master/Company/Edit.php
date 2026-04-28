<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Empresa', 'guard' => 'master'])]
class Edit extends Component
{
    public Company $company;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|size:14')]
    public $cnpj = '';

    #[Rule('required|email')]
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

    #[Rule('nullable|string|min:8|max:255|confirmed')]
    public ?string $new_password = null;

    public ?string $new_password_confirmation = null;

    public function mount(Company $company)
    {
        $this->company = $company->load('companyPlans');

        $this->name          = $company->name;
        $this->cnpj          = $company->cnpj;
        $this->email         = $company->email;
        $this->phone         = $company->phone;
        $this->address       = $company->address;
        $this->city          = $company->city;
        $this->state         = $company->state;
        $this->zip_code      = $company->zip_code;
        $this->is_active     = $company->is_active;
        $this->contact_name  = $company->contact_name;
        $this->contact_role  = $company->contact_role;
        $this->contact_phone = $company->contact_phone;
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'name'          => $this->name,
            'cnpj'          => $this->cnpj,
            'email'         => $this->email,
            'phone'         => preg_replace('/\D/', '', $this->phone),
            'address'       => $this->address,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => preg_replace('/\D/', '', $this->zip_code),
            'is_active'     => $this->is_active,
            'contact_name'  => $this->contact_name,
            'contact_role'  => $this->contact_role,
            'contact_phone' => $this->contact_phone,
        ];

        if (!empty($this->new_password)) {
            $payload['password'] = $this->new_password;
        }

        $this->company->update($payload);

        $this->reset(['new_password', 'new_password_confirmation']);

        session()->flash('message', 'Empresa atualizada com sucesso!');

        return $this->redirect(route('master.company.show', ['company' => $this->company->id]));
    }

    public function render()
    {
        return view('livewire.master.company.edit');
    }
}

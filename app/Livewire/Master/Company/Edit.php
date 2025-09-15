<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
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



    public function mount(Company $company)
    {
        $this->company = $company->load('companyPlans');

        $this->name = $company->name;
        $this->cnpj = $company->cnpj;
        $this->email = $company->email;
        $this->phone = $company->phone;
        $this->address = $company->address;
        $this->city = $company->city;
        $this->state = $company->state;
        $this->zip_code = $company->zip_code;
        $this->is_active = $company->is_active;
    }



    public function save()
    {
        $this->validate();

        $this->company->update([
            'name' => $this->name,
            'cnpj' => $this->cnpj,
            'email' => $this->email,
            'phone' => preg_replace('/\D/', '', $this->phone),
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => preg_replace('/\D/', '', $this->zip_code),
            'is_active' => $this->is_active,
        ]);



        session()->flash('message', 'Empresa atualizada com sucesso!');

        return $this->redirect(route('master.company.show', ['company' => $this->company->id]));
    }

    public function render()
    {
        return view('livewire.master.company.edit');
    }
}

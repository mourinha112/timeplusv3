<?php

namespace App\Livewire\Company\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Perfil da Empresa', 'guard' => 'company'])]
class Show extends Component
{
    public $company;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|max:14')]
    public $cnpj = '';

    #[Rule('required|email|max:255')]
    public $email = '';

    #[Rule('nullable|string|max:15')]
    public $phone = '';

    #[Rule('nullable|string|max:8')]
    public $zip_code = '';

    #[Rule('nullable|string|max:255')]
    public $address = '';

    #[Rule('nullable|string|max:255')]
    public $city = '';

    #[Rule('nullable|string|max:2')]
    public $state = '';

    public function mount()
    {
        $this->company = Auth::guard('company')->user();

        $this->name     = $this->company->name;
        $this->cnpj     = $this->company->cnpj;
        $this->email    = $this->company->email;
        $this->phone    = $this->company->phone ?? '';
        $this->zip_code = $this->company->zip_code ?? '';
        $this->address  = $this->company->address ?? '';
        $this->city     = $this->company->city ?? '';
        $this->state    = $this->company->state ?? '';
    }

    public function save()
    {
        $this->validate();

        $this->company->update([
            'name'     => $this->name,
            'cnpj'     => $this->cnpj,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'zip_code' => $this->zip_code,
            'address'  => $this->address,
            'city'     => $this->city,
            'state'    => $this->state,
        ]);

        session()->flash('success', 'Perfil atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.company.profile.show');
    }
}

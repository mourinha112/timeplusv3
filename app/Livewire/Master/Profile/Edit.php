<?php

namespace App\Livewire\Master\Profile;

use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Meu Perfil', 'guard' => 'master'])]
class Edit extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email')]
    public $email = '';

    #[Rule('nullable|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function mount()
    {
        $master = auth('master')->user();
        $this->name = $master->name;
        $this->email = $master->email;
    }

    public function save()
    {
        if ($this->password === '') {
            $this->password = null;
            $this->password_confirmation = null;
        }

        $this->validate();

        $master = auth('master')->user();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        $master->update($data);

        session()->flash('message', 'Perfil atualizado com sucesso!');

        return $this->redirect(route('master.dashboard.show'));
    }

    public function render()
    {
        return view('livewire.master.profile.edit');
    }
}

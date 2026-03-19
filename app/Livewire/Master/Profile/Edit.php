<?php

namespace App\Livewire\Master\Profile;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Meu Perfil', 'guard' => 'master'])]
class Edit extends Component
{
    public $name = '';
    public $email = '';
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
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $this->validate($rules);

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

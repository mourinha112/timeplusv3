<?php

namespace App\Livewire\Master\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Usuário', 'guard' => 'master'])]
class Edit extends Component
{
    public User $user;

    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $cpf = '';
    public $birth_date = '';
    public $is_active = true;
    public $password = '';
    public $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;

        $this->name         = $user->name;
        $this->email        = $user->email;
        $this->phone_number = $user->phone_number;
        $this->cpf          = $user->cpf;
        $this->birth_date   = $user->birth_date;
        $this->is_active    = $user->is_active;
    }

    public function save()
    {
        $rules = [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'phone_number' => 'nullable|string|max:15',
            'cpf'          => 'nullable|string|max:14',
            'birth_date'   => 'nullable|date',
            'is_active'    => 'boolean',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name'         => $this->name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'cpf'          => $this->cpf,
            'birth_date'   => $this->birth_date,
            'is_active'    => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        $this->user->update($data);

        session()->flash('message', 'Usuário atualizado com sucesso!');

        return $this->redirect(route('master.user.personal-data.show', ['user' => $this->user->id]));
    }

    public function render()
    {
        return view('livewire.master.user.edit');
    }
}

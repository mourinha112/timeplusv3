<?php

namespace App\Livewire\Master\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Usuário', 'guard' => 'master'])]
class Edit extends Component
{
    public User $user;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email')]
    public $email = '';

    #[Rule('nullable|string|max:15')]
    public $phone_number = '';

    #[Rule('nullable|string|max:14')]
    public $cpf = '';

    #[Rule('nullable|date')]
    public $birth_date = '';

    #[Rule('boolean')]
    public $is_active = true;

    #[Rule('nullable|string|min:8|confirmed')]
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
        $this->validate();

        $data = [
            'name'         => $this->name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'cpf'          => $this->cpf,
            'birth_date'   => $this->birth_date,
            'is_active'    => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
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

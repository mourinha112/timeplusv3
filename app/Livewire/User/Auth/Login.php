<?php

namespace App\Livewire\User\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public ?string $email;

    public ?string $password;

    public function submit()
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return;
        }

        return $this->redirectRoute('welcome');
    }

    public function render()
    {
        return view('livewire.user.auth.login');
    }
}

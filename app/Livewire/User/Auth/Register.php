<?php

namespace App\Livewire\User\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Register extends Component
{
    public ?string $name;

    public ?string $email;

    public ?string $password;

    public ?string $password_confirmation;

    public function submit(): void
    {
        User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt($this->password),
        ]);
    }

    public function render(): View
    {
        return view('livewire.user.auth.register');
    }
}

<?php

namespace App\Livewire\User\Auth;

use App\Models\User;
use App\Notifications\User\WelcomeNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'max:14', 'unique:users,cpf', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'])]
    public ?string $cpf = null;

    #[Rule(['required', 'max:20', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'])]
    public ?string $phone_number = null;

    #[Rule(['required', 'max:255', 'email', 'unique:users,email'])]
    public ?string $email = null;

    #[Rule(['required', 'min:8', 'max:255'])]
    public ?string $password = null;

    public function submit(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name'         => $this->name,
            'email'        => $this->email,
            'cpf'          => $this->cpf,
            'phone_number' => $this->phone_number,
            'password'     => bcrypt($this->password),
        ]);

        Auth::login($user);

        $user->notify(new WelcomeNotification());

        $this->redirectRoute('user.dashboard.show');
    }

    public function render(): View
    {
        return view('livewire.user.auth.register');
    }
}

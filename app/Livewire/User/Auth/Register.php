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

    #[Rule(['required', 'max:255', 'email', 'unique:users,email'])]
    public ?string $email = null;

    #[Rule(['required', 'min:8', 'max:255', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function submit(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt($this->password),
        ]);

        Auth::login($user);

        $user->notify(new WelcomeNotification());

        $this->redirectRoute('welcome');
    }

    public function render(): View
    {
        return view('livewire.user.auth.register');
    }
}

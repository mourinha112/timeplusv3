<?php

namespace App\Livewire\Specialist\Auth;

use App\Models\Specialist;
use App\Notifications\Specialist\WelcomeNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Registrar'])]
class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'max:14', 'unique:specialists,cpf', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'])]
    public ?string $cpf = null;

    #[Rule(['required', 'max:20', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'])]
    public ?string $phone_number = null;

    #[Rule(['required', 'max:255', 'email', 'unique:specialists,email'])]
    public ?string $email = null;

    #[Rule(['required', 'min:8', 'max:255'])]
    public ?string $password = null;

    public function submit(): void
    {
        $this->validate();

        $specialist = Specialist::query()->create([
            'name'         => $this->name,
            'email'        => $this->email,
            'cpf'          => $this->cpf,
            'phone_number' => $this->phone_number,
            'password'     => bcrypt($this->password),
        ]);

        Auth::guard('specialist')->login($specialist, true);

        // $specialist->notify(new WelcomeNotification());

        $this->redirectRoute('specialist.appointment.index');
    }

    public function render(): View
    {
        return view('livewire.specialist.auth.register');
    }
}

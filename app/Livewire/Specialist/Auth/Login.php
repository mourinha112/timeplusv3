<?php

namespace App\Livewire\Specialist\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Entrar'])]
class Login extends Component
{
    #[Rule(['required', 'email', 'max:255'])]
    public ?string $email = null;

    #[Rule(['required', 'max:255'])]
    public ?string $password = null;

    public function submit()
    {
        $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $this->addError('rateLimiter', trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]));

            return;
        }

        if (!Auth::guard('specialist')->attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }

        return $this->redirectRoute('specialist.appointment.index');
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower('rate-limiter::' . $this->email . '|' . request()->ip()));
    }

    public function render()
    {
        return view('livewire.specialist.auth.login');
    }
}

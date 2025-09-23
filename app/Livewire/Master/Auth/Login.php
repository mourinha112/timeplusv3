<?php

namespace App\Livewire\Master\Auth;

use Illuminate\Support\Facades\{Auth, Log, RateLimiter};
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Entrar'])]
class Login extends Component
{
    #[Rule(['required', 'email', 'max:255'])]
    public ?string $email = null;

    #[Rule(['required', 'max:255', 'min:8'])]
    public ?string $password = null;

    public function submit(): void
    {
        $this->validate();

        try {
            if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
                // $this->addError('rateLimiter', trans('auth.throttle'));

                LivewireAlert::title('Limite de tentativas excedido')
                    ->text('Você excedeu o número de tentativas de acesso.')
                    ->error()
                    ->show();

                $this->reset(['email', 'password']);

                return;
            }

            if (!Auth::guard('master')->attempt(['email' => $this->email, 'password' => $this->password])) {
                RateLimiter::hit($this->throttleKey());

                // $this->addError('invalidCredentials', trans('auth.failed'));

                LivewireAlert::title('Credenciais inválidas')
                    ->text('As credenciais fornecidas estão incorretas.')
                    ->error()
                    ->show();

                $this->reset(['password']);

                return;
            }

            redirect()->to(route('master.dashboard.show'), true);
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar entrar na sessão.')
                ->error()
                ->show();
        }
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower('rate-limiter::' . $this->email . '|' . request()->ip()));
    }

    public function render()
    {
        return view('livewire.master.auth.login');
    }
}

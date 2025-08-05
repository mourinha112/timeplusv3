<?php

namespace App\Livewire\User\Auth;

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

    #[Rule(['required', 'max:255'])]
    public ?string $password = null;

    public function submit()
    {
        $this->validate();

        try {
            if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
                LivewireAlert::title('Limite de tentativas excedido')
                    ->text('Você excedeu o número de tentativas de login.')
                    ->error()
                    ->show();

                $this->reset(['email', 'password']);

                return;
            }

            if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
                RateLimiter::hit($this->throttleKey());

                LivewireAlert::title('Credenciais inválidas')
                    ->text('As credenciais fornecidas estão incorretas')
                    ->error()
                    ->show();

                $this->reset(['password']);

                return;
            }

            return $this->redirectRoute('user.dashboard.show');
        } catch (\Exception $e) {
            Log::error('Erro interno::' . Login::class, [
                'message' => $e->getMessage(),
                'email'   => $this->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar fazer login.')
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
        return view('livewire.user.auth.login');
    }
}

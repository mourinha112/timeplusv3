<?php

namespace App\Livewire\Specialist\Auth;

use App\Models\Specialist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, Hash, Log, RateLimiter};
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
                LivewireAlert::title('Limite de tentativas excedido')
                    ->text('Você excedeu o número de tentativas de acesso.')
                    ->error()
                    ->show();

                $this->reset(['email', 'password']);

                return;
            }

            // Debug temporário
            $specialist = Specialist::where('email', $this->email)->first();
            Log::info('DEBUG_LOGIN_ESPECIALISTA', [
                'email'          => $this->email,
                'password_typed' => $this->password,
                'found'          => $specialist ? true : false,
                'hash_in_db'     => $specialist?->getAuthPassword(),
                'hash_check'     => $specialist ? Hash::check($this->password, $specialist->getAuthPassword()) : null,
                'is_active'      => $specialist?->is_active,
            ]);

            if (!Auth::guard('specialist')->attempt(['email' => $this->email, 'password' => $this->password])) {
                RateLimiter::hit($this->throttleKey());

                LivewireAlert::title('Credenciais inválidas')
                    ->text('As credenciais fornecidas estão incorretas.')
                    ->error()
                    ->show();

                $this->reset(['password']);

                return;
            }

            $this->redirect(route('specialist.dashboard.show'), true);
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

    public function render(): View
    {
        return view('livewire.specialist.auth.login');
    }
}

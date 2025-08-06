<?php

namespace App\Livewire\User\Auth;

use App\Models\User;
use App\Notifications\User\PasswordResetNotification;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Redefinição de Senha'])]
class PasswordReset extends Component
{
    public ?User $user = null;

    public ?bool $expired = false;

    #[Rule(['required', 'min:8', 'max:255', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount($token)
    {
        $this->user = User::where('recovery_password_token', $token)->first();

        if (!$this->user) {
            $this->redirect(route('user.auth.login'));
            return;
        }

        if (!$this->user->recovery_password_token_expires_at || $this->user->recovery_password_token_expires_at < now()) {
            $this->expired = true;
            return;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->user->password = bcrypt($this->password);

            $this->user->recovery_password_token = null;
            $this->user->recovery_password_token_expires_at = null;

            $this->user->save();

            $this->reset(['password', 'password_confirmation']);

            // $this->user->notify(new PasswordResetNotification());

            LivewireAlert::title('Sucesso!')
                ->text('Sua senha foi redefinida com sucesso.')
                ->success()
                ->show();

            $this->redirectRoute('user.auth.login');
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar redefinir a senha.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.user.auth.password-reset');
    }
}

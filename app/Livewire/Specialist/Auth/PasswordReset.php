<?php

namespace App\Livewire\Specialist\Auth;

use App\Models\Specialist;
use App\Notifications\Specialist\PasswordResetNotification;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Redefinição de Senha'])]
class PasswordReset extends Component
{
    public ?Specialist $specialist = null;

    public ?bool $expired = false;

    #[Rule(['required', 'min:8', 'max:255', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount($token)
    {
        $this->specialist = Specialist::where('recovery_password_token', $token)->first();

        if (!$this->specialist) {
            $this->redirect(route('specialist.auth.login'));

            return;
        }

        if (!$this->specialist->recovery_password_token_expires_at || $this->specialist->recovery_password_token_expires_at < now()) {
            $this->expired = true;

            return;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->specialist->password = bcrypt($this->password);

            $this->specialist->recovery_password_token            = null;
            $this->specialist->recovery_password_token_expires_at = null;

            $this->specialist->save();

            $this->reset(['password', 'password_confirmation']);

            $this->specialist->notify(new PasswordResetNotification());

            LivewireAlert::title('Sucesso!')
                ->text('Sua senha foi redefinida com sucesso.')
                ->success()
                ->show();

            $this->redirectRoute('specialist.auth.login');
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
        return view('livewire.specialist.auth.password-reset');
    }
}

<?php

namespace App\Livewire\Company\Auth;

use App\Models\Company;
use App\Notifications\Company\PasswordResetNotification;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Redefinição de Senha'])]
class PasswordReset extends Component
{
    public ?Company $company = null;

    public ?bool $expired = false;

    #[Rule(['required', 'min:8', 'max:255', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount($token)
    {
        $this->company = Company::where('recovery_password_token', $token)->first();

        if (!$this->company) {
            $this->redirect(route('company.auth.login'));

            return;
        }

        if (!$this->company->recovery_password_token_expires_at || $this->company->recovery_password_token_expires_at < now()) {
            $this->expired = true;

            return;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->company->password = $this->password;

            $this->company->recovery_password_token            = null;
            $this->company->recovery_password_token_expires_at = null;

            $this->company->save();

            $this->reset(['password', 'password_confirmation']);

            $this->company->notify(new PasswordResetNotification());

            LivewireAlert::title('Sucesso!')
                ->text('Sua senha foi redefinida com sucesso.')
                ->success()
                ->show();

            $this->redirectRoute('company.auth.login');
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
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
        return view('livewire.company.auth.password-reset');
    }
}

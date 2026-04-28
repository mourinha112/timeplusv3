<?php

namespace App\Livewire\Company\Auth;

use App\Models\Company;
use App\Notifications\Company\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Recuperação de Senha'])]
class PasswordRecovery extends Component
{
    #[Rule(['required', 'email', 'max:255'])]
    public ?string $email = null;

    public function submit()
    {
        $this->validate();

        try {
            $company = Company::where('email', $this->email)->first();

            if (!$company) {
                LivewireAlert::title('Sucesso!')
                    ->text('Um e-mail foi enviado com as instruções para recuperação de senha.')
                    ->success()
                    ->show();

                $this->reset(['email']);

                return;
            }

            if ($company->recovery_password_token_expires_at && $company->recovery_password_token_expires_at > now()) {
                LivewireAlert::title('Atenção!')
                    ->text('Já existe uma solicitação de recuperação de senha pendente.')
                    ->warning()
                    ->show();

                $this->reset(['email']);

                return;
            }

            $company->recovery_password_token            = Str::random(60);
            $company->recovery_password_token_expires_at = now()->addMinutes(30);

            $company->save();

            $company->notify(new PasswordRecoveryNotification());

            $this->reset(['email']);

            LivewireAlert::title('Sucesso!')
                ->text('Um e-mail foi enviado com as instruções para recuperação de senha.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar recuperar a senha.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.company.auth.password-recovery');
    }
}

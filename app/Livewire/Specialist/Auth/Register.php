<?php

namespace App\Livewire\Specialist\Auth;

use App\Models\Specialist;
use App\Notifications\Specialist\WelcomeNotification;
use App\Rules\FormattedCpf;
use App\Rules\FormattedPhoneNumber;
use App\Rules\ValidatedCpf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Registrar'])]
class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'max:14', 'unique:specialists,cpf', new FormattedCpf(), new ValidatedCpf()])]
    public ?string $cpf = null;

    #[Rule(['required', 'max:20', new FormattedPhoneNumber()])]
    public ?string $phone_number = null;

    public ?string $birth_date = null;

    #[Rule(['required', 'max:255', 'email', 'unique:specialists,email'])]
    public ?string $email = null;

    #[Rule(['required', 'min:8', 'max:255'])]
    public ?string $password = null;

    public function rules(): array
    {
        return [
            'birth_date' => ['required', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(18)->toDateString()],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        try {
            $specialist = Specialist::query()->create([
                'name'         => $this->name,
                'email'        => $this->email,
                'cpf'          => $this->cpf,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
                'password'     => bcrypt($this->password),
            ]);

            Auth::guard('specialist')->login($specialist, true);

            $specialist->notify(new WelcomeNotification());

            $this->redirectRoute('specialist.appointment.index');
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->email,
                'cpf'     => $this->cpf,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar se cadastrar.')
                ->error()
                ->show();
        }
    }

    public function render(): View
    {
        return view('livewire.specialist.auth.register');
    }
}

<?php

namespace App\Livewire\Specialist\Auth;

use App\Models\{Specialist, State};
use App\Notifications\Specialist\WelcomeNotification;
use App\Rules\{FormattedCpf, FormattedPhoneNumber, ValidatedCpf, ValidCrp};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Registrar'])]
class Register extends Component
{
    public ?string $name = null;
    public ?string $cpf = null;
    public ?string $phone_number = null;
    public ?string $birth_date = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;
    public ?int $state_id = null;
    public ?string $crp = null;

    public function rules(): array
    {
        return [
            'name'         => ['required', 'max:255'],
            'cpf'          => ['required', 'max:14', 'unique:specialists,cpf', new FormattedCpf(), new ValidatedCpf()],
            'phone_number' => ['required', 'max:20', new FormattedPhoneNumber()],
            'birth_date'   => ['required', 'date_format:d/m/Y', 'after:01/01/1900', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'email'        => ['required', 'max:255', 'email', 'unique:specialists,email'],
            'password'     => ['required', 'min:8', 'max:255', 'confirmed'],
            'state_id'     => ['required', 'exists:states,id'],
            'crp'          => ['required', 'min:8', 'max:8', 'unique:specialists,crp', new ValidCrp()],
        ];
    }

    #[Computed]
    public function states()
    {
        return State::orderBy('name')->get();
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
                'password'     => $this->password,
                'state_id'     => $this->state_id,
                'crp'          => $this->crp,
            ]);

            Auth::guard('specialist')->login($specialist, true);

            $specialist->notify(new WelcomeNotification());

            $this->redirectRoute('specialist.dashboard.show');
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

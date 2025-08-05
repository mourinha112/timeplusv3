<?php

namespace App\Livewire\Specialist\Profile;

use App\Models\{Gender, State};
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados pessoais', 'guard' => 'specialist'])]
class PersonalDetail extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name;

    public ?string $email;

    #[Rule(['required', 'max:20', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'])]
    public ?string $phone_number;

    public ?string $birth_date;

    #[Rule(['required', 'exists:genders,id'])]
    public ?int $gender_id;

    #[Rule(['required', 'exists:states,id'])]
    public ?int $state_id;

    public ?bool $lgbtqia = false;

    public function rules(): array
    {
        return [
            'birth_date' => ['required', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(18)->toDateString()],
        ];
    }

    #[Computed]
    public function genders()
    {
        return Gender::all();
    }

    #[Computed]
    public function states()
    {
        return State::all();
    }

    public function mount()
    {
        $specialist = Auth::guard('specialist')->user();

        $this->name         = $specialist->name;
        $this->email        = $specialist->email;
        $this->phone_number = $specialist->phone_number;
        $this->birth_date   = $specialist->birth_date;
        $this->gender_id    = $specialist->gender_id;
        $this->state_id     = $specialist->state_id;
        $this->lgbtqia      = $specialist->lgbtqia;
    }

    public function submit()
    {
        $this->validate();

        $specialist = Auth::guard('specialist')->user();

        $specialist->update([
            'name'         => $this->name,
            'phone_number' => $this->phone_number,
            'birth_date'   => $this->birth_date,
            'gender_id'    => $this->gender_id,
            'state_id'     => $this->state_id,
            'lgbtqia'      => $this->lgbtqia,
        ]);

        LivewireAlert::title('Perfil atualizado com sucesso!')
            ->text('Suas informações foram salvas com sucesso.')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.specialist.profile.personal-detail');
    }
}

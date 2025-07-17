<?php

namespace App\Livewire\Specialist\Onboarding;

use App\Models\Gender;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class PersonalDetail extends Component
{
    #[Rule(['required', 'exists:genders,id'])]
    public ?int $gender_id = null;

    #[Rule(['required', 'exists:specialties,id'])]
    public ?int $specialty_id = null;

    #[Rule(['required', 'min:5', 'max:10', 'unique:specialists,crp'])]
    public ?string $crp = null;

    #[Rule(['required', 'date_format:Y', 'before_or_equal:now', 'after_or_equal:1900'])]
    public ?int $year_started_acting = null;

    #[Computed]
    public function genders()
    {
        return Gender::all();
    }

    #[Computed]
    public function specialties()
    {
        return Specialty::all();
    }

    public function submit(): void
    {
        $this->validate();

        $specialist = Auth::guard('specialist')->user();

        $specialist->update([
            'gender_id' => $this->gender_id,
            'specialty_id' => $this->specialty_id,
            'crp' => $this->crp,
            'year_started_acting' => $this->year_started_acting,
            'onboarding_step' => 'completed',
        ]);
    }

    public function render()
    {
        return view('livewire.specialist.onboarding.personal-detail');
    }
}

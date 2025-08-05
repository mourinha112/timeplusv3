<?php

namespace App\Livewire\Specialist\Onboarding;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Dados Profissionais', 'guard' => 'specialist'])]
class ProfessionalDetail extends Component
{
    #[Rule(['required', 'integer', 'min:0', 'max:1000'])]
    public ?int $appointment_value = null;

    #[Rule(['required', 'min:5', 'max:500'])]
    public ?string $summary = null;

    #[Rule(['required', 'min:5', 'max:500'])]
    public ?string $description = null;

    public function submit()
    {
        $this->validate();

        $specialist = Auth::guard('specialist')->user();

        $specialist->update([
            'appointment_value' => $this->appointment_value,
            'summary'           => $this->summary,
            'description'       => $this->description,
            'onboarding_step'   => 'completed',
        ]);

        return $this->redirect(route('specialist.appointment.index'));
    }

    public function render()
    {
        return view('livewire.specialist.onboarding.professional-detail');
    }
}

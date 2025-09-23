<?php

namespace App\Livewire\User\Specialist;

use App\Models\Specialist;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Currículo do Especialista', 'guard' => 'user'])]
class Show extends Component
{
    public Specialist $specialist;

    public function mount(Specialist $specialist): void
    {
        $this->specialist = $specialist->load(['specialty', 'reasons']);
    }

    public function render()
    {
        return view('livewire.user.specialist.show', [
            'experienceYears' => $this->calculateExperienceYears(),
        ]);
    }

    private function calculateExperienceYears(): ?int
    {
        if (!$this->specialist->year_started_acting) {
            return null;
        }

        return max(0, now()->year - (int) $this->specialist->year_started_acting);
    }
}

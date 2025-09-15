<?php

namespace App\Livewire\Master\Specialist\PersonalData;

use App\Models\Specialist;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Especialista', 'guard' => 'master'])]
class Show extends Component
{
    public Specialist $specialist;

    public function mount(Specialist $specialist): void
    {
        $this->specialist = $specialist->load(['appointments', 'specialty', 'state']);
    }

    public function render()
    {
        return view('livewire.master.specialist.personal-data.show');
    }
}

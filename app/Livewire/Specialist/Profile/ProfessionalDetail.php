<?php

namespace App\Livewire\Specialist\Profile;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados profissionais', 'guard' => 'specialist'])]
class ProfessionalDetail extends Component
{
    public function render()
    {
        return view('livewire.specialist.profile.professional-detail');
    }
}

<?php

namespace App\Livewire\User\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public function render()
    {
        return view('livewire.user.dashboard.show');
    }
}

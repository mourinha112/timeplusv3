<?php

namespace App\Livewire\Master\Availability;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Disponibilidades', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.availability.index');
    }
}

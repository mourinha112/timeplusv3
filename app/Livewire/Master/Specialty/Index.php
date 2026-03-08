<?php

namespace App\Livewire\Master\Specialty;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Especialidades', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.specialty.index');
    }
}

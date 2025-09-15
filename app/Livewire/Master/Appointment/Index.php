<?php

namespace App\Livewire\Master\Appointment;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.appointment.index');
    }
}

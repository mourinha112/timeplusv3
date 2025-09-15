<?php

namespace App\Livewire\Master\Appointment;

use App\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Agendamento', 'guard' => 'master'])]
class Show extends Component
{
    public Appointment $appointment;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment->load(['user', 'specialist', 'payment']);
    }

    public function render()
    {
        return view('livewire.master.appointment.show');
    }
}

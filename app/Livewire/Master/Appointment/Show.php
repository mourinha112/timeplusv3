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
        $this->appointment = $appointment->load(['user', 'specialist', 'payment', 'room']);
    }

    public function cancelAppointment(): void
    {
        $this->appointment->update(['status' => 'cancelled']);

        if ($this->appointment->room) {
            $this->appointment->room->update(['status' => 'closed', 'closed_at' => now()]);
        }

        session()->flash('message', 'Agendamento cancelado com sucesso!');

        $this->redirect(route('master.appointment.show', ['appointment' => $this->appointment->id]));
    }

    public function render()
    {
        return view('livewire.master.appointment.show');
    }
}

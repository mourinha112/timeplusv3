<?php

namespace App\Livewire\Specialist\Appointment;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'specialist'])]
class Index extends Component
{
    #[Computed]
    public function appointments()
    {
        return Auth::guard('specialist')->user()->appointments()
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.specialist.appointment.index');
    }
}

<?php

namespace App\Livewire\Specialist\Appointment;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

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

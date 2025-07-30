<?php

namespace App\Livewire\User\Appointment;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed]
    public function appointments()
    {
        return Auth::user()->appointments()
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.appointment.index');
    }
}

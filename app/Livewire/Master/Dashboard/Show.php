<?php

namespace App\Livewire\Master\Dashboard;

use App\Models\{Appointment, Payment, Specialist, User};
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard', 'guard' => 'master'])]
class Show extends Component
{
    #[Computed()]
    public function users()
    {
        return User::count();
    }

    #[Computed()]
    public function specialists()
    {
        return Specialist::count();
    }

    #[Computed()]
    public function appointments()
    {
        return Appointment::count();
    }

    #[Computed()]
    public function payments()
    {
        return Payment::count();
    }

    public function render()
    {
        return view('livewire.master.dashboard.show');
    }
}

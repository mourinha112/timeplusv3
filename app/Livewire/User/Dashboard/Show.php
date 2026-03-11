<?php

namespace App\Livewire\User\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard', 'guard' => 'user'])]
class Show extends Component
{
    #[Computed]
    public function nextAppointment()
    {
        return Auth::user()->appointments()
            ->with(['specialist', 'payment', 'room'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query->where('appointment_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('appointment_date', now()->toDateString())
                            ->where('appointment_time', '>=', now()->format('H:i:s'));
                    });
            })
            ->whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->first();
    }

    public function hasRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'open';
    }

    public function hasScheduledRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'closed';
    }

    public function getRoomOpenTime($appointment)
    {
        if (!$this->hasScheduledRoom($appointment)) {
            return null;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        return $appointmentDateTime->subMinutes(10);
    }

    public function render()
    {
        return view('livewire.user.dashboard.show');
    }
}

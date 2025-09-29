<?php

namespace App\Livewire\Specialist\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Clientes', 'guard' => 'specialist'])]
class Index extends Component
{
    #[Computed]
    public function clients()
    {
        return User::whereHas('appointments', function ($query) {
            $query->where('specialist_id', Auth::guard('specialist')->id())
                  ->where('status', 'completed');
        })
        ->with(['appointments' => function ($query) {
            $query->where('specialist_id', Auth::guard('specialist')->id())
                  ->where('status', 'completed')
                  ->orderBy('appointment_date', 'desc')
                  ->orderBy('appointment_time', 'desc')
                  ->limit(1); // Apenas a mais recente completed
        }])
        ->get();
    }

    public function getLastAppointment($client)
    {
        return $client->appointments->first();
    }

    public function getLastAppointmentDate($client)
    {
        $lastAppointment = $this->getLastAppointment($client);

        return $lastAppointment ?
            \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') :
            'Nunca';
    }

    public function render()
    {
        return view('livewire.specialist.client.index');
    }
}

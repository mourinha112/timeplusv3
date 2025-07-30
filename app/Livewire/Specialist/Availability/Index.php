<?php

namespace App\Livewire\Specialist\Availability;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Disponibilidades', 'guard' => 'specialist'])]
class Index extends Component
{
    #[Computed]
    public function availabilities()
    {
        return Auth::guard('specialist')->user()->availabilities()
            ->orderBy('available_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.specialist.availability.index');
    }
}

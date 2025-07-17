<?php

namespace App\Livewire\Specialist\Availability;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

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

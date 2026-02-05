<?php

namespace App\Livewire\User\Specialist;

use App\Models\Specialist;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Especialistas', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed()]
    public function specialists()
    {
        return Specialist::with(['specialty', 'reasons'])
            ->whereHas('availabilities', function ($query) {
                $query->where('available_date', '>=', now()->toDateString());
            })
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.specialist.index');
    }
}

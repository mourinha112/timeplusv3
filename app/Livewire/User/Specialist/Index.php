<?php

namespace App\Livewire\User\Specialist;

use App\Models\Specialist;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Especialistas', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed()]
    public function specialists()
    {
        return Specialist::all();
    }

    public function render()
    {
        return view('livewire.user.specialist.index');
    }
}

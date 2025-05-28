<?php

namespace App\Livewire\User\Specialist;

use App\Models\Specialist;
use Livewire\Attributes\Computed;
use Livewire\Component;

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

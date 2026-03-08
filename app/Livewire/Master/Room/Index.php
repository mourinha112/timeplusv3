<?php

namespace App\Livewire\Master\Room;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Salas de Video', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.room.index');
    }
}

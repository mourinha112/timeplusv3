<?php

namespace App\Livewire\Master\Reason;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Motivos de Consulta', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.reason.index');
    }
}

<?php

namespace App\Livewire\Master\Plan;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Planos da Plataforma', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.plan.index');
    }
}

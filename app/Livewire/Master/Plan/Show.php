<?php

namespace App\Livewire\Master\Plan;

use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Plano', 'guard' => 'master'])]
class Show extends Component
{
    public Plan $plan;

    public function mount(Plan $plan): void
    {
        $this->plan = $plan;
    }

    public function render()
    {
        return view('livewire.master.plan.show');
    }
}
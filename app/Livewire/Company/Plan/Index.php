<?php

namespace App\Livewire\Company\Plan;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Planos da Empresa', 'guard' => 'company'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.company.plan.index');
    }
}

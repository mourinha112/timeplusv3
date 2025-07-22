<?php

namespace App\Livewire\User\Plan;

use App\Models\Plan;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    #[Computed]
    public function plans()
    {
        return Plan::all();
    }

    public function subscribe($planId)
    {
        dd('ASSINOOOOOU!', $planId);
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}

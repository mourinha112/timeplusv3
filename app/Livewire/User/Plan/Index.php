<?php

namespace App\Livewire\User\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
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
        $plan = Plan::findOrFail($planId);

        $subscribe = Auth::user()->subscribes()->where('end_date', '>', now())->first();

        if($subscribe) {
            return session()->flash('error', 'Você já possui uma assinatura ativa.');
        }

        Auth::user()->subscribes()->create([
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
        ]);

        return session()->flash('success', 'Assinatura realizada com sucesso!');
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}

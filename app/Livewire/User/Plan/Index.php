<?php

namespace App\Livewire\User\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Livewire;

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

        if ($subscribe) {
            LivewireAlert::title('Erro!')
                ->text('Você já possui uma assinatura ativa.')
                ->error()
                ->show();
            return;
        }

        Auth::user()->subscribes()->create([
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
        ]);

        LivewireAlert::title('Sucesso!')
            ->text('Assinatura realizada com sucesso!')
            ->success()
            ->show();
        return;
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}

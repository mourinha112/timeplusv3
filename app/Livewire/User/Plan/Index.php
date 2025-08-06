<?php

namespace App\Livewire\User\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Planos', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed]
    public function plans()
    {
        return Plan::all();
    }

    public function subscribe($planId)
    {
        try {
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
                'plan_id'    => $plan->id,
                'start_date' => now(),
                'end_date'   => now()->addDays($plan->duration_days),
            ]);

            LivewireAlert::title('Sucesso!')
                ->text('Assinatura realizada com sucesso!')
                ->success()
                ->show();

            return $this->redirect(route('user.subscribe.show'));
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'user_id' => Auth::id(),
                'plan_id' => $planId,
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar realizar a assinatura.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}

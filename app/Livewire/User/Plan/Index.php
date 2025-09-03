<?php

namespace App\Livewire\User\Plan;

use App\Models\{Plan, Subscribe};
use Illuminate\Support\Facades\{Auth};
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

    public function mount()
    {
        $subscribe = Subscribe::where('end_date', '>', now())
            ->where('cancelled_date', null)
            ->where('user_id', Auth::id())
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->first();

        if ($subscribe) {
            LivewireAlert::title('Erro!')->text('Você já possui uma assinatura ativa.')->error()->show();
            $this->redirect(route('user.subscribe.show'));

            return;
        }
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}

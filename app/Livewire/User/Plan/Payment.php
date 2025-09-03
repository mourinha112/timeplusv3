<?php

namespace App\Livewire\User\Plan;

use App\Models\{Plan, Subscribe};
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Contratar planos', 'guard' => 'user'])]
class Payment extends Component
{
    public $plan;

    #[Rule(['required', 'string', 'in:credit_card,pix'])]
    public ?string $payment_method = 'credit_card';

    public ?string $selected_payment_method = null;

    public function mount($plan_id)
    {
        $this->plan = Plan::findOrFail($plan_id);

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

    public function submit()
    {
        $this->validate();

        $this->selected_payment_method = $this->payment_method;
    }

    public function render()
    {
        return view('livewire.user.plan.payment');
    }
}

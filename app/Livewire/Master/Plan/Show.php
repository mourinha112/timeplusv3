<?php

namespace App\Livewire\Master\Plan;

use App\Models\{Plan, Subscribe};
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Plano', 'guard' => 'master'])]
class Show extends Component
{
    public Plan $plan;

    public function mount(Plan $plan): void
    {
        $this->plan = $plan;
    }

    #[Computed()]
    public function subscriberStats(): array
    {
        $base = Subscribe::where('plan_id', $this->plan->id);

        return [
            'total'     => (clone $base)->count(),
            'active'    => (clone $base)->where('billing_status', Subscribe::STATUS_ACTIVE)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->whereHas('payments', fn ($query) => $query->where('status', 'paid'))
                ->count(),
            'cancelled' => (clone $base)->where('billing_status', Subscribe::STATUS_CANCELLED)->count(),
            'expired'   => (clone $base)->where('billing_status', Subscribe::STATUS_EXPIRED)->count(),
        ];
    }

    public function render()
    {
        $latestSubscribers = Subscribe::with(['user', 'payments'])
            ->where('plan_id', $this->plan->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('livewire.master.plan.show', [
            'latestSubscribers' => $latestSubscribers,
        ]);
    }
}

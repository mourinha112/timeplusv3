<?php

namespace App\Livewire\Master\Finance;

use App\Models\{Appointment, Payment, Specialist};
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes Financeiros', 'guard' => 'master'])]
class Show extends Component
{
    public Specialist $specialist;

    public function mount(Specialist $specialist): void
    {
        $this->specialist = $specialist->load('paymentProfile');
    }

    #[Computed()]
    public function totalEarned()
    {
        return Payment::where('status', 'paid')
            ->whereHasMorph('payable', [Appointment::class], function ($query) {
                $query->where('specialist_id', $this->specialist->id);
            })
            ->sum('amount');
    }

    #[Computed()]
    public function platformFee()
    {
        $fee = $this->specialist->paymentProfile?->platform_fee_percentage ?? 20;
        return round($this->totalEarned * $fee / 100, 2);
    }

    #[Computed()]
    public function specialistBalance()
    {
        return round($this->totalEarned - $this->platformFee, 2);
    }

    #[Computed()]
    public function completedSessions()
    {
        return Appointment::where('specialist_id', $this->specialist->id)
            ->where('status', 'completed')
            ->count();
    }

    #[Computed()]
    public function recentPayments()
    {
        return Payment::where('status', 'paid')
            ->whereHasMorph('payable', [Appointment::class], function ($query) {
                $query->where('specialist_id', $this->specialist->id);
            })
            ->with('payable')
            ->orderByDesc('paid_at')
            ->limit(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.master.finance.show');
    }
}

<?php

namespace App\Livewire\Master\Finance;

use App\Models\{Appointment, Payment, Specialist, SpecialistPaymentProfile};
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Financeiro - Especialistas', 'guard' => 'master'])]
class Index extends Component
{
    #[Computed()]
    public function totalRevenue()
    {
        return Payment::where('status', 'paid')
            ->whereHasMorph('payable', [Appointment::class])
            ->sum('amount');
    }

    #[Computed()]
    public function totalSpecialistPayout()
    {
        $specialists = Specialist::with('paymentProfile')->get();
        $total = 0;

        foreach ($specialists as $specialist) {
            $earned = Payment::where('status', 'paid')
                ->whereHasMorph('payable', [Appointment::class], function ($query) use ($specialist) {
                    $query->where('specialist_id', $specialist->id);
                })
                ->sum('amount');

            $fee = $specialist->paymentProfile?->platform_fee_percentage ?? 20;
            $total += $earned * (1 - $fee / 100);
        }

        return round($total, 2);
    }

    #[Computed()]
    public function totalPlatformFee()
    {
        return round($this->totalRevenue - $this->totalSpecialistPayout, 2);
    }

    #[Computed()]
    public function profilesCount()
    {
        return SpecialistPaymentProfile::count();
    }

    public function render()
    {
        return view('livewire.master.finance.index');
    }
}

<?php

namespace App\Livewire\Master\Payment;

use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Relatório de Baixas', 'guard' => 'master'])]
class PayoffReport extends Component
{
    use WithPagination;

    public ?string $from = null;

    public ?string $to = null;

    public function mount(): void
    {
        $this->from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->to   = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function applyFilter(): void
    {
        $this->resetPage();
    }

    #[Computed()]
    public function totalAmount(): float
    {
        return (float) $this->buildQuery()->sum('amount');
    }

    private function buildQuery()
    {
        $query = Payment::query()->whereNotNull('manual_paid_at');

        if ($this->from) {
            $query->whereDate('manual_paid_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('manual_paid_at', '<=', $this->to);
        }

        return $query;
    }

    public function render()
    {
        $payments = $this->buildQuery()
            ->with(['payable', 'company'])
            ->orderByDesc('manual_paid_at')
            ->paginate(20);

        return view('livewire.master.payment.payoff-report', [
            'payments' => $payments,
        ]);
    }
}

<?php

namespace App\Livewire\Master\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Pagamento', 'guard' => 'master'])]
class Show extends Component
{
    public Payment $payment;

    public bool $showPayoffModal = false;

    #[Rule('nullable|string|max:500')]
    public ?string $payoffNote = null;

    public function mount(Payment $payment): void
    {
        $this->payment = $payment->load(['payable', 'company']);
    }

    public function openPayoffModal(): void
    {
        $this->payoffNote      = null;
        $this->showPayoffModal = true;
    }

    public function closePayoffModal(): void
    {
        $this->showPayoffModal = false;
    }

    public function payoff(): void
    {
        $this->validate();

        if ($this->payment->manual_paid_at) {
            LivewireAlert::title('Já está dado baixa.')
                ->warning()
                ->show();

            return;
        }

        $this->payment->update([
            'status'                   => 'paid',
            'paid_at'                  => $this->payment->paid_at ?? now(),
            'manual_paid_at'           => now(),
            'manual_paid_by_master_id' => Auth::guard('master')->id(),
            'manual_paid_note'         => $this->payoffNote,
        ]);

        $this->payment->refresh();
        $this->showPayoffModal = false;

        LivewireAlert::title('Baixa registrada!')
            ->text('Pagamento marcado como recebido manualmente.')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.master.payment.show');
    }
}

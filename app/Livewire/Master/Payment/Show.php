<?php

namespace App\Livewire\Master\Payment;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Pagamento', 'guard' => 'master'])]
class Show extends Component
{
    public Payment $payment;

    public function mount(Payment $payment): void
    {
        $this->payment = $payment->load(['payable', 'company']);
    }

    public function render()
    {
        return view('livewire.master.payment.show');
    }
}

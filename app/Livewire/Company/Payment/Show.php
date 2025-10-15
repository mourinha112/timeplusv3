<?php

namespace App\Livewire\Company\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Pagamento', 'guard' => 'company'])]
class Show extends Component
{
    public Payment $payment;

    public function mount(Payment $payment)
    {
        $company = Auth::guard('company')->user();

        // Verificar se o pagamento pertence a esta empresa
        if ($payment->company_id !== $company->id) {
            abort(403, 'Acesso negado a este pagamento.');
        }

        $this->payment = $payment->load(['payable.user', 'payable.specialist', 'company']);
    }

    public function render()
    {
        return view('livewire.company.payment.show');
    }
}

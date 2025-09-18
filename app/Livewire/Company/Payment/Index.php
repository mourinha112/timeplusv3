<?php

namespace App\Livewire\Company\Payment;

use App\Models\{Payment};
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Pagamentos dos Funcionários', 'guard' => 'company'])]
class Index extends Component
{
    public $totalPayments;

    public $totalDiscount;

    public function mount()
    {
        $this->calculateSummary();
    }

    public function calculateSummary()
    {
        $company = Auth::guard('company')->user();

        // Consulta simplificada para evitar erros de relacionamento
        $payments = Payment::where('company_id', $company->id)
            ->where('status', 'paid')
            ->get();

        $this->totalPayments = $payments->count();
        $this->totalDiscount = $payments->sum('discount_value') ?: 0;
    }

    public function render()
    {
        return view('livewire.company.payment.index');
    }
}

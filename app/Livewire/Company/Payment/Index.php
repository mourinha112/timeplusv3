<?php

namespace App\Livewire\Company\Payment;

use App\Models\CompanyPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Pagamentos', 'guard' => 'company'])]
class Index extends Component
{
    public $payments = [];
    public $currentPlan;
    public $nextPayment;

    public function mount()
    {
        // Dados temporariamente desabilitados para evitar erros
        $this->currentPlan = null;
        $this->nextPayment = null;
        $this->payments = collect([]);

        // TODO: Implementar integração com sistema de pagamentos quando estiver completo
        // $company = Auth::guard('company')->user();
        // $this->currentPlan = $company->companyPlans()->where('is_active', true)->with('plan')->first();
        // Simular dados de pagamento conforme necessário
    }

    public function render()
    {
        return view('livewire.company.payment.index');
    }
}

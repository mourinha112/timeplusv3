<?php

namespace App\Livewire\Company\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard Empresa', 'guard' => 'company'])]
class Show extends Component
{
    public $company = null;

    public function mount()
    {
        $this->company = Auth::guard('company')->user();

        // Carregar relacionamentos necessários de uma vez
        $this->company->load(['employees', 'companyPlans', 'payments']);
    }

    public function render()
    {
        return view('livewire.company.dashboard.show', [
            'company'              => $this->company,
            'activeEmployeesCount' => $this->company->activeEmployees()->count(),
            'totalEmployeesCount'  => $this->company->employees()->count(),
        ]);
    }
}

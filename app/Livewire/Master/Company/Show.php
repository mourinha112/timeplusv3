<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes da Empresa', 'guard' => 'master'])]
class Show extends Component
{
    public Company $company;

    public function mount(Company $company): void
    {
        $this->company = $company->load(['companyPlan', 'payments']);
    }

    public function render()
    {
        return view('livewire.master.company.show');
    }
}

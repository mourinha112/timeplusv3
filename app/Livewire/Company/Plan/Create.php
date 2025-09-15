<?php

namespace App\Livewire\Company\Plan;

use App\Models\CompanyPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Criar Plano', 'guard' => 'company'])]
class Create extends Component
{
    public $company = null;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|numeric|min:0.01|max:100')]
    public $discount_percentage = '';

    public function mount()
    {
        $this->company = Auth::guard('company')->user();
    }

    public function save()
    {
        $this->validate();

        CompanyPlan::create([
            'company_id'          => $this->company->id,
            'name'                => $this->name,
            'discount_percentage' => $this->discount_percentage,
            'is_active'           => true,
        ]);

        session()->flash('success', 'Plano criado com sucesso!');

        return $this->redirect(route('company.plan.index'));
    }

    public function render()
    {
        return view('livewire.company.plan.create');
    }
}

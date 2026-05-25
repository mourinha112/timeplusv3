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

    #[Rule('required|in:per_employee,credit_pack')]
    public string $billing_model = 'per_employee';

    #[Rule('required_if:billing_model,credit_pack|integer|min:1')]
    public $monthly_credits = 1;

    #[Rule('required|numeric|min:30')]
    public $price_per_unit = 60.00;

    public function mount()
    {
        $this->company = Auth::guard('company')->user();
    }

    public function updatedBillingModel(string $value): void
    {
        $this->price_per_unit = $value === 'credit_pack' ? 30.00 : 60.00;
    }

    public function save()
    {
        $this->validate();

        $pricePerUnit = $this->billing_model === 'credit_pack' ? 30.00 : 60.00;

        CompanyPlan::create([
            'company_id'          => $this->company->id,
            'name'                => $this->name,
            'discount_percentage' => 0,
            'billing_model'       => $this->billing_model,
            'monthly_credits'     => $this->billing_model === 'credit_pack' ? $this->monthly_credits : 0,
            'price_per_unit'      => $pricePerUnit,
            'billing_status'      => 'active',
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

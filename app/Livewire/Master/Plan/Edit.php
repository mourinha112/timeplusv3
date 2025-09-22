<?php

namespace App\Livewire\Master\Plan;

use App\Models\Plan;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Plano', 'guard' => 'master'])]
class Edit extends Component
{
    public Plan $plan;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|numeric|min:0.01')]
    public $price = '';

    #[Rule('required|numeric|min:0|max:100')]
    public $discount_percentage = '';

    #[Rule('required|integer|min:1')]
    public $duration_days = '';

    public function mount(Plan $plan): void
    {
        $this->plan = $plan;
        
        // Preencher os campos com os dados atuais
        $this->name = $this->plan->name;
        $this->price = $this->plan->price;
        $this->discount_percentage = $this->plan->discount_percentage;
        $this->duration_days = $this->plan->duration_days;
    }

    public function save()
    {
        $this->validate();

        $this->plan->update([
            'name' => $this->name,
            'price' => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'duration_days' => $this->duration_days,
        ]);

        session()->flash('success', 'Plano atualizado com sucesso!');

        return $this->redirect(route('master.plan.index'));
    }

    public function render()
    {
        return view('livewire.master.plan.edit');
    }
}
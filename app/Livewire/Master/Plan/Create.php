<?php

namespace App\Livewire\Master\Plan;

use App\Models\Plan;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Novo Plano', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string|max:1000')]
    public $description = '';

    #[Rule('required|numeric|min:0.01')]
    public $price = '';

    #[Rule('required|numeric|min:0|max:100')]
    public $discount_percentage = '0';

    #[Rule('required|integer|min:1')]
    public $duration_days = '';

    #[Rule('nullable|integer|min:1')]
    public $max_sessions = null;

    public function save()
    {
        $this->validate();

        Plan::create([
            'name'                => $this->name,
            'description'         => $this->description,
            'price'               => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'duration_days'       => $this->duration_days,
            'max_sessions'        => $this->max_sessions,
        ]);

        session()->flash('success', 'Plano criado com sucesso!');

        return $this->redirect(route('master.plan.index'));
    }

    public function render()
    {
        return view('livewire.master.plan.create');
    }
}

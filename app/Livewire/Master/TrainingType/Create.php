<?php

namespace App\Livewire\Master\TrainingType;

use App\Models\TrainingType;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Novo Tipo de Formacao', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255|unique:training_types,name')]
    public $name = '';

    public function save()
    {
        $this->validate();
        TrainingType::create(['name' => $this->name]);
        session()->flash('success', 'Tipo de formacao criado com sucesso!');
        return $this->redirect(route('master.training-type.index'));
    }

    public function render()
    {
        return view('livewire.master.training-type.create');
    }
}

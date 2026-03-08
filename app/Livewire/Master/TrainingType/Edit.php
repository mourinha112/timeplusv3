<?php

namespace App\Livewire\Master\TrainingType;

use App\Models\TrainingType;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Tipo de Formacao', 'guard' => 'master'])]
class Edit extends Component
{
    public TrainingType $trainingType;

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount(TrainingType $trainingType)
    {
        $this->trainingType = $trainingType;
        $this->name = $trainingType->name;
    }

    public function save()
    {
        $this->validate();
        $this->trainingType->update(['name' => $this->name]);
        session()->flash('success', 'Tipo de formacao atualizado com sucesso!');
        return $this->redirect(route('master.training-type.index'));
    }

    public function render()
    {
        return view('livewire.master.training-type.edit');
    }
}

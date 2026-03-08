<?php

namespace App\Livewire\Master\Specialty;

use App\Models\Specialty;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Especialidade', 'guard' => 'master'])]
class Edit extends Component
{
    public Specialty $specialty;

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount(Specialty $specialty)
    {
        $this->specialty = $specialty;
        $this->name = $specialty->name;
    }

    public function save()
    {
        $this->validate();
        $this->specialty->update(['name' => $this->name]);
        session()->flash('success', 'Especialidade atualizada com sucesso!');
        return $this->redirect(route('master.specialty.index'));
    }

    public function render()
    {
        return view('livewire.master.specialty.edit');
    }
}

<?php

namespace App\Livewire\Master\Specialty;

use App\Models\Specialty;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Nova Especialidade', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255|unique:specialties,name')]
    public $name = '';

    public function save()
    {
        $this->validate();
        Specialty::create(['name' => $this->name]);
        session()->flash('success', 'Especialidade criada com sucesso!');
        return $this->redirect(route('master.specialty.index'));
    }

    public function render()
    {
        return view('livewire.master.specialty.create');
    }
}

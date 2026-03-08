<?php

namespace App\Livewire\Master\Gender;

use App\Models\Gender;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Genero', 'guard' => 'master'])]
class Edit extends Component
{
    public Gender $gender;

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount(Gender $gender)
    {
        $this->gender = $gender;
        $this->name = $gender->name;
    }

    public function save()
    {
        $this->validate();
        $this->gender->update(['name' => $this->name]);
        session()->flash('success', 'Genero atualizado com sucesso!');
        return $this->redirect(route('master.gender.index'));
    }

    public function render()
    {
        return view('livewire.master.gender.edit');
    }
}

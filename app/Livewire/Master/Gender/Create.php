<?php

namespace App\Livewire\Master\Gender;

use App\Models\Gender;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Novo Genero', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255|unique:genders,name')]
    public $name = '';

    public function save()
    {
        $this->validate();
        Gender::create(['name' => $this->name]);
        session()->flash('success', 'Genero criado com sucesso!');
        return $this->redirect(route('master.gender.index'));
    }

    public function render()
    {
        return view('livewire.master.gender.create');
    }
}

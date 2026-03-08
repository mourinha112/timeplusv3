<?php

namespace App\Livewire\Master\Reason;

use App\Models\Reason;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Novo Motivo', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|string|max:255|unique:reasons,name')]
    public $name = '';

    public function save()
    {
        $this->validate();
        Reason::create(['name' => $this->name]);
        session()->flash('success', 'Motivo criado com sucesso!');
        return $this->redirect(route('master.reason.index'));
    }

    public function render()
    {
        return view('livewire.master.reason.create');
    }
}

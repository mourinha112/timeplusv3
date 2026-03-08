<?php

namespace App\Livewire\Master\Reason;

use App\Models\Reason;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Motivo', 'guard' => 'master'])]
class Edit extends Component
{
    public Reason $reason;

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount(Reason $reason)
    {
        $this->reason = $reason;
        $this->name = $reason->name;
    }

    public function save()
    {
        $this->validate();
        $this->reason->update(['name' => $this->name]);
        session()->flash('success', 'Motivo atualizado com sucesso!');
        return $this->redirect(route('master.reason.index'));
    }

    public function render()
    {
        return view('livewire.master.reason.edit');
    }
}

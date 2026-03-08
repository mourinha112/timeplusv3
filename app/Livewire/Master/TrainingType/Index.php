<?php

namespace App\Livewire\Master\TrainingType;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Tipos de Formacao', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.training-type.index');
    }
}

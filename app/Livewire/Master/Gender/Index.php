<?php

namespace App\Livewire\Master\Gender;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Generos', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.gender.index');
    }
}

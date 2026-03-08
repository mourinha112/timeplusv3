<?php

namespace App\Livewire\Master\Subscribe;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Assinaturas', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.subscribe.index');
    }
}

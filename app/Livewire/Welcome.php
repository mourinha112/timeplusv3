<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Página inicial'])]
class Welcome extends Component
{
    public function render()
    {
        return view('livewire.welcome');
    }
}

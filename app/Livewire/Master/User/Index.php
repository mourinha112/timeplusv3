<?php

namespace App\Livewire\Master\User;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Usuários', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.user.index');
    }
}


<?php

namespace App\Livewire\Master\Company;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Empresas', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.company.index');
    }
}

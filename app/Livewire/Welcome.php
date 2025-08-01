<?php

namespace App\Livewire;

use Livewire\Component;

class Welcome extends Component
{
    public function mount(){
        return redirect()->route('user.auth.login');
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}

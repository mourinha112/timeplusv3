<?php

namespace App\Livewire;

use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        return redirect()->route('user.auth.login');
        // return view('livewire.welcome');
    }
}

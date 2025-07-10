<?php

namespace App\Livewire\User\Specialist;

use Livewire\Component;

class Card extends Component
{
    public $specialist;

    public function render()
    {
        return view('livewire.user.specialist.card');
    }
}

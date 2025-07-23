<?php

namespace App\Livewire\User\Specialist;

use Livewire\Component;

class Card extends Component
{
    public $specialist;
    public $favorited = false;

    public function favorite(){
        $this->favorited = !$this->favorited;
    }

    public function render()
    {
        return view('livewire.user.specialist.card');
    }
}

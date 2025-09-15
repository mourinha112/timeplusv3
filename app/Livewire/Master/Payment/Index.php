<?php

namespace App\Livewire\Master\Payment;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Pagamentos', 'guard' => 'master'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.payment.index');
    }
}

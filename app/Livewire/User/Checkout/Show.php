<?php

namespace App\Livewire\User\Checkout;

use Livewire\Component;

class Show extends Component
{
    public $payable;

    public string $payment_method;

    public function selectPaymentMethod(string $method)
    {
        $this->payment_method = $method;
    }

    public function render()
    {
        return view('livewire.user.checkout.show');
    }
}

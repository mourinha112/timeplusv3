<?php

namespace App\Services\Pagarme;

class PagarmeManagerService
{
    public function customer(): CustomerService
    {
        return new CustomerService();
    }

    public function payment(): PaymentService
    {
        return new PaymentService();
    }
}

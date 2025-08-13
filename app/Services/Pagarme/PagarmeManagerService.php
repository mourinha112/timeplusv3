<?php

namespace App\Services\Pagarme;

class PagarmeManagerService
{
    public function customer(): CustomerService
    {
        return new CustomerService();
    }
}

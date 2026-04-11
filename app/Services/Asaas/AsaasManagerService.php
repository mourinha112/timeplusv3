<?php

namespace App\Services\Asaas;

class AsaasManagerService
{
    public function customer(): CustomerService
    {
        return new CustomerService();
    }

    public function charge(): ChargeService
    {
        return new ChargeService();
    }

    public function payment(): PaymentService
    {
        return new PaymentService();
    }

    public function subscription(): SubscriptionService
    {
        return new SubscriptionService();
    }
}

<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Asaas\CustomerService customer()
 * @method static \App\Services\Asaas\ChargeService charge()
 * @method static \App\Services\Asaas\PaymentService payment()
 */
class Asaas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'asaas';
    }
}

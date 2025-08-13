<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Pagarme\CustomerService customer()
 */
class Pagarme extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pagarme';
    }
}
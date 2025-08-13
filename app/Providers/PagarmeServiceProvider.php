<?php

namespace App\Providers;

use App\Services\Pagarme\PagarmeManagerService;
use Illuminate\Support\ServiceProvider;

class PagarmeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('pagarme', function ($app) {
            return new PagarmeManagerService();
        });
    }

    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Services\Asaas\AsaasManagerService;
use Illuminate\Support\ServiceProvider;

class AsaasServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('asaas', function ($app) {
            return new AsaasManagerService();
        });
    }

    public function boot(): void
    {
        //
    }
}

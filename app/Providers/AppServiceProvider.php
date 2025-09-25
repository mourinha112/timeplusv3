<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Abrir salas agendadas a cada minuto (10min antes da consulta)
            $schedule->command('rooms:open-scheduled')
                ->everyMinute()
                ->withoutOverlapping()
                ->runInBackground();

            // Fechar salas expiradas a cada minuto (1h após a consulta)
            $schedule->command('rooms:close-expired')
                ->everyMinute()
                ->withoutOverlapping()
                ->runInBackground();
        });
    }
}

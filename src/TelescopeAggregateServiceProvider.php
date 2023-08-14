<?php

namespace Goodmain\TelescopeAggregate;

use Goodmain\TelescopeAggregate\Console\Commands\TelescopeAggregate;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class TelescopeAggregateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Telescope Aggregate', fn () => ['Version' => '0.1.0']);

        $this->publishes([
            __DIR__.'/../config/telescope-aggregate.php' => config_path('telescope-aggregate.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                TelescopeAggregate::class,
            ]);
        }
    }
}
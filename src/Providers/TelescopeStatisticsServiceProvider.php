<?php

namespace Goodmain\TelescopeStatistics\Providers;

use Goodmain\TelescopeStatistics\Console\InstallCommand;
use Goodmain\TelescopeStatistics\Console\PublishCommand;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class TelescopeStatisticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        AboutCommand::add('Telescope Statistics', fn () => ['Version' => '0.1.0']);

        $this->publishes([
            __DIR__.'/../config/telescope-statistics.php' => config_path('telescope-statistics.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class,
            ]);
        }
    }
}
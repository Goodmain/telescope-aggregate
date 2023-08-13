<?php

namespace Goodmain\TelescopeStatistics\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope-statistics:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Telescope Statistics resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //$this->comment('Publishing Telescope Service Provider...');
        //$this->callSilent('vendor:publish', ['--tag' => 'telescope-provider']);

        //$this->comment('Publishing Telescope Assets...');
        //$this->callSilent('vendor:publish', ['--tag' => 'telescope-assets']);

        $this->comment('Publishing Telescope Statistics Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-statistics-config']);

        $this->addCommandsIntoSchedule();

        $this->info('Telescope Statistics installed successfully.');
    }

    /**
     * Register the Telescope service provider in the application configuration file.
     *
     * @return void
     */
    protected function addCommandsIntoSchedule()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $kernel = file_get_contents(app_path('Console\Kernel.php'));

        if (Str::contains($kernel, 'telescope-aggregate')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($kernel, "\r\n"),
            "\r" => substr_count($kernel, "\r"),
            "\n" => substr_count($kernel, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];



        /*file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\TelescopeServiceProvider::class,".$eol,
            $kernel
        ));

        file_put_contents(app_path('Providers/TelescopeServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/TelescopeServiceProvider.php'))
        ));*/
    }
}
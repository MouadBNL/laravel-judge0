<?php

namespace Mouadbnl\Judge0;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Mouadbnl\Judge0\Commands\ImportLanguages;
use Mouadbnl\Judge0\Commands\ImportStatuses;
use RuntimeException;

class Judge0ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/judge0.php', 'judge0');

        // Setting default judge0.config
        // config([
        //     'judge0.config' => config('judge0.drivers.'.config('judge0.default'))
        // ]);

        $this->app->bind('judge0', function()
        {
            $class = config('judge0.drivers.'. config('judge0.default') .'.class');
            if(! class_exists($class)){
                throw new RuntimeException("Can not find class ". $class .", please set a valide full class name in judge0 config file.");
                
            }
            return new $class();
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                ImportStatuses::class,
                ImportLanguages::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/judge0.php' => config_path('judge0.php'),
            ], 'judge0-config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_judge0_tables.php.stub' => $this->getMigrationFileName('create_judge0_tables.php'),
            ], 'judge0-migrations');

        }

        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }


    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}

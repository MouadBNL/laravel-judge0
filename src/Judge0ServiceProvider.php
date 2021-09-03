<?php

namespace Mouadbnl\Judge0;

use Illuminate\Support\ServiceProvider;
use Mouadbnl\Judge0\Commands\ImportStatuses;
use RuntimeException;

class Judge0ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/judge0.php', 'judge0');

        // Setting default judge0.config
        config([
            'judge0.config' => config('judge0.drivers.'.config('judge0.default'))
        ]);

        $this->app->bind('judge0', function()
        {
            $class = config('judge0.config.class');
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
            ]);

            $this->publishes([
                __DIR__.'/../config/judge0.php' => config_path('judge0.php'),
            ], 'judge0-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => base_path('database/migrations'),
            ], 'judge0-migration');

        }

        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

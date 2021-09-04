<?php

namespace Mouadbnl\Judge0;

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
                __DIR__.'/../database/migrations/create_users_table.php.stub' => database_path('migrations/'. date("Y-m-d_His") .'_create_users_table.php'),
            ], 'judge0-migrations');

        }

        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

<?php

namespace Mouadbnl\Judge0;

use Illuminate\Support\ServiceProvider;

class Judge0ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('judge0', function()
        {
            return new Judge0();
        });
    }

    public function boot()
    {
        
    }
}

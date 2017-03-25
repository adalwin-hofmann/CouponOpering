<?php namespace SOE\Loggers;

use Illuminate\Support\ServiceProvider;
use SOE\Loggers\BackofficeUpdated;

class LoggerServiceProvider extends ServiceProvider
{

    /**
    * Boot the Logger Events
    *
    * @return void
    */
    public function boot()
    {
        $this->app->events->subscribe(new BackofficeUpdated);
    }

    public function register()
    {

    }

}
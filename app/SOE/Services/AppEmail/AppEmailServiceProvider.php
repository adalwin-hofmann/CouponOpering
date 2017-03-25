<?php namespace SOE\Services\AppEmail;

use Illuminate\Support\ServiceProvider;

class AppEmailServiceProvider extends ServiceProvider
{
    public function register()
    {
        \App::bind('AppEmailInterface', 'SOE\Services\AppEmail\InfusionSoftAppEmail');
    }
}
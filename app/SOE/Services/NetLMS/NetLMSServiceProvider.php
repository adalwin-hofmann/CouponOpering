<?php namespace SOE\Services\NetLMS;

use Illuminate\Support\ServiceProvider;
use App;

class NetLMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        App::bind('NetLMSAPIInterface', '\SOE\Services\NetLMS\NetLMSAPI');
    }
}
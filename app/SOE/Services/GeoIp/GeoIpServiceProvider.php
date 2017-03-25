<?php namespace SOE\Services\GeoIp;

use Illuminate\Support\ServiceProvider;
use App;
use Illuminate\Foundation\AliasLoader;

/**
*
* @api
*/

class GeoIpServiceProvider extends ServiceProvider
{
    public function register()
    {
        App::bind('geoip', function()
        {
            return new Ip2LocationGeoIp;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        App::booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('GeoIp', 'SOE\Facades\GeoIp');
        });
    }
}
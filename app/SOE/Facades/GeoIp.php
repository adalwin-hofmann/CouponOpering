<?php namespace SOE\Facades;

use Illuminate\Support\Facades\Facade;

class GeoIp extends Facade {

    protected static function getFacadeAccessor() { return 'geoip'; }

}
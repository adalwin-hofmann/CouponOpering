<?php namespace SOE\Services\GeoIp;

interface GeoIpInterface
{
    public function getGeoIp($output = 'js', $use_current = false);

    public function getMarket();
}
<?php

interface MerchantRepository
{
    public function getLocationsByDistance($page = 0, $limit = 0, $latitude, $longitude);

    public function getMerchantStates($page = 0, $limit = 0);

    public function getLocationsByState($state, $page = 0, $limit = 0);

    public function getMerchantCities($state, $page = 0, $limit = 0);

    public function getLocationsByCity($city, $state, $page = 0, $limit = 0);

    public function getBySlug($slug);

    public function findNearestLocation($latitude, $longitude);

    public function findNearestLocationBySlug($slug, $latitude, $longitude);

    public function getByName($name, $page = 0, $limit = 0);

    public function category();

    public function subcategory();

    public function logo();

    /***** API METHODS *****/

    public function apiGetLocationsByDistance();

    public function apiGetMerchantStates();

    public function apiGetLocationsByState();

    public function apiGetMerchantCities();

    public function apiGetLocationsByCity();

    public function apiGetByName();
}
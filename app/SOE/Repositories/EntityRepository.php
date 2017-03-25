<?php

interface EntityRepository
{
    public function getRecommendations(PersonInterface $person, $geoip = array(), $limit = 0);

    public function getByCategory(PersonInterface $person, $city, $state, $lat, $lng, $type = null, $category_id = null, $sort = 'nearest', $page = 0, $limit = 12, $radius = 0);

    public function getSimilar(MerchantRepository $merchant, PersonInterface $person);

    public function getFeatured(PersonInterface $person, $type, $state, $latitude, $longitude);

    /***** API METHODS *****/

    public function apiGetByCategory();

    public function apiGetFeatured();
}
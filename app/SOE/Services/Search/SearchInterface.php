<?php namespace SOE\Services\Search;

interface SearchInterface
{
    public function getResults($query, $type, $facet='', $facet_type='', $page = 0, $limit = 12, $order, $filter_with_coupons = 'active', $with_radius = true);

    /***** API METHODS *****/

    public function apiGetResults();
}
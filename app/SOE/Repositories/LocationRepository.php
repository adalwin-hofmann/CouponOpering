<?php

interface LocationRepository
{
    public function getEntities($page = 0, $limit = 12);

    public function getReviews($page = 0, $limit = 12);

    public function getByFranchiseId($franchise_id, $page = 0, $limit = 0, $params = array());

    public function franchise();

    /***** API METHODS *****/

    public function apiGetEntities();

    public function apiGetReviews();

    public function apiGetByFranchiseId();
}
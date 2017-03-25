<?php

interface BaseRepositoryInterface
{

    public function blank();

    public function create(array $attributes = array());

    public function find($id);

    public function get(array $filters = array(), $limit = 0, $page = 0);

    /***** API METHODS *****/

    public function apiCreate();

    public function apiFind();

    public function apiGet();

    public function apiUpdate();
}
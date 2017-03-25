<?php

interface RepositoryInterface
{
    public function __get($key);

    public function __set($key, $value);

    public function blank();

    public function create(array $attributes = array());

    public function find($id);

    public function get(array $filters = array(), $limit = 0, $page = 0);

    public function save();
}

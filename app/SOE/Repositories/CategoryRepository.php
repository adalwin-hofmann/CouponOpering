<?php

interface CategoryRepository
{
    public function findBySlug($slug);

    public function getByParentSlug($slug, $page = 0, $limit = 0);

    /***** API METHODS *****/

    public function apiGetByParentSlug();
}
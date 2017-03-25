<?php

interface RoleRepository
{
    public function findByName($name);

    public function attach(RuleRepository $rule);

    public function remove(RuleRepository $rule);

    public function getRules(array $filters = array(), $limit = 0, $page = 0);

    /***** API METHODS *****/
}
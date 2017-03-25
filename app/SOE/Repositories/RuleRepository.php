<?php

/**
*
* @api
*/

interface RuleRepository
{
    public function findByGroupAndAction($group, $action);

    /***** API METHODS *****/
}
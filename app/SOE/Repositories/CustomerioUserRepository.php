<?php

/**
*
* @api
*/

interface CustomerioUserRepository
{
    public function findByEmail($email);

    /***** API METHODS *****/
}
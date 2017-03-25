<?php

interface UserLocationRepository
{
    public function setLocation($user, $lat = '', $lng = '', $city = '', $state = '', $use_current = false);

    /***** API METHODS *****/
}
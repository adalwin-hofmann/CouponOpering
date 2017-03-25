<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* UserLocation.
*
* A location of a User.
*
* @author Caleb Beery <cbeery@saveoneverything.com>
*
*/

class UserLocation extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_locations';

    public static function boot()
    {
        parent::boot();

        UserLocation::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
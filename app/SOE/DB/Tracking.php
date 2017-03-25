<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* Tracking.
*
* Table for tacking codes.
*
* @author Matt Crandell <mrcrandell@saveon.com>
*
*/

class Tracking extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tracking';

    public static function boot()
    {
        parent::boot();

        Tag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    
}
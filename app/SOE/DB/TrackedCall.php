<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* TrackedCall.
*
* Table for calls tracked by Twilio.
*
* @author Caleb Beery <cbeery@saveon.com>
*
*/

class TrackedCall extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tracked_calls';

    public static function boot()
    {
        parent::boot();

        TrackedCall::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    
}
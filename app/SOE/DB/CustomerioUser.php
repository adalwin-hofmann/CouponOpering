<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class CustomerioUser extends Eloquent 
{
    /** Force database to use table "customerio_users".  */
    protected $table = 'customerio_users';

    public static function boot()
    {
        parent::boot();

        CustomerioUser::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
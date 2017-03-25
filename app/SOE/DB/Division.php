<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Division extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Division::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
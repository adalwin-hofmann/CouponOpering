<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class YipitDivision extends Eloquent 
{
    /** Force database to use table "yipit_divisions".  */
    protected $table = 'yipit_divisions';

    public static function boot()
    {
        parent::boot();

        YipitDivision::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
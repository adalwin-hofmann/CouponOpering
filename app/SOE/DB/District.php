<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class District extends Eloquent 
{
    /** Force database to use table "districts".  */
    protected $table = 'districts';

    public static function boot()
    {
        parent::boot();

        District::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
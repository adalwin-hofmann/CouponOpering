<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class CjProduct extends Eloquent 
{
    /** Force database to use table "cj_products".  */
    protected $table = 'cj_products';

    public static function boot()
    {
        parent::boot();

        CjProduct::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
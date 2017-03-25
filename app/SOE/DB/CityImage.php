<?php namespace SOE\DB;

/**
*
* @api
*/

use SOE\Extensions\Eloquent;

class CityImage extends Eloquent 
{
    /** Force database to use table "city_images".  */
    protected $table = 'city_images';

    public static function boot()
    {
        parent::boot();

        CityImage::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class YipitTag extends Eloquent 
{
	/** Force database to use table "yipit_tags".  */
    protected $table = 'yipit_tags';

    public static function boot()
    {
        parent::boot();

        YipitTag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class YipitBannedMerchant extends Eloquent 
{
	/** Force database to use table "yipit_banned_merchants".  */
    protected $table = 'yipit_banned_merchants';

    public static function boot()
    {
        parent::boot();

        YipitTag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
<?php namespace SOE\DB;

/**
*
* @api
*/

use SOE\Extensions\Eloquent;

class Asset extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Asset::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    public function assetable()
    {
        return $this->morphTo();
    }
}
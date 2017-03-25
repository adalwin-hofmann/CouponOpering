<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class BannerEntity extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        BannerEntity::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
    * Get the Banner associated with this BannerEntity.
    *
    * @return Banner
    */
    public function banner()
    {
        return $this->belongsTo('Banner');
    }

}
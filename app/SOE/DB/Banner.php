<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Banner extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Banner::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
    * Get all BannerEntities associated with this Banner.
    *
    * @return BannerEntities
    */
    public function entities()
    {
        return $this->hasMany('BannerEntity');
    }

}
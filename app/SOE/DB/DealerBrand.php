<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class DealerBrand extends Eloquent 
{
    protected $table = 'dealer_brands';

    public static function boot()
    {
        parent::boot();

        DealerBrand::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
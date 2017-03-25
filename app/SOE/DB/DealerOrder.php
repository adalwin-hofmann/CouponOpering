<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class DealerOrder extends Eloquent 
{
    protected $table = 'dealer_orders';

    public static function boot()
    {
        parent::boot();

        DealerOrder::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
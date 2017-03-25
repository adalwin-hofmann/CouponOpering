<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class LeadPurchase extends Eloquent 
{
    
    public static function boot()
    {
        parent::boot();

        LeadPurchase::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
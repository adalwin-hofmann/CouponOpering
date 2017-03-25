<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Quote extends Eloquent 
{
    protected $table = 'quotes';

    public static function boot()
    {
        parent::boot();

        Quote::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}
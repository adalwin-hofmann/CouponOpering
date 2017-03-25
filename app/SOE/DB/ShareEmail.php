<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ShareEmail extends Eloquent 
{

    public static function boot()
    {
        parent::boot();

        ShareEmail::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}
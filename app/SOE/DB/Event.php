<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Event extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Event::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
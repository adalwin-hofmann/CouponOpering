<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Report extends Eloquent 
{
    protected $table = 'reports';

    public static function boot()
    {
        parent::boot();

        Report::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}
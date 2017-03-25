<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class LeadEmail extends Eloquent 
{
    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        LeadEmail::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
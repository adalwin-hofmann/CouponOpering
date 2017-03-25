<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class SohiSurvey extends Eloquent 
{
    protected $table = 'sohi_surveys';

    public static function boot()
    {
        parent::boot();

        SohiSurvey::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class SeoContent extends Eloquent 
{
    protected $table = 'seo_contents';

    public static function boot()
    {
        parent::boot();

        SeoContent::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}
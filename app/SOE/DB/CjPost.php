<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class CjPost extends Eloquent 
{
    /** Force database to use table "cj_posts".  */
    protected $table = 'cj_posts';

    public static function boot()
    {
        parent::boot();

        CjPost::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
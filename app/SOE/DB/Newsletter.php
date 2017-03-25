<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Newsletter extends Eloquent 
{
    /** Force database to use table "newsletters".  */
    protected $table = 'newsletters';

    public static function boot()
    {
        parent::boot();

        Newsletter::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    
}
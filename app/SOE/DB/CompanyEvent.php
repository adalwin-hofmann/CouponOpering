<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class CompanyEvent extends Eloquent 
{
    /** Force database to use table "company_events".  */
    protected $table = 'company_events';

    public static function boot()
    {
        parent::boot();

        District::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
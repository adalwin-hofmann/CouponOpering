<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class CompanyPost extends Eloquent 
{
    /** Force database to use table "company_posts".  */
    protected $table = 'company_posts';

    public static function boot()
    {
        parent::boot();

        CompanyPost::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class FranchiseDistrict extends Eloquent 
{
    /** Force database to use table "franchise_districts".  */
    protected $table = 'franchise_districts';

    public static function boot()
    {
        parent::boot();

        FranchiseDistrict::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
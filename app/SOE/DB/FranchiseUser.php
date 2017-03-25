<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class FranchiseUser extends Eloquent 
{
    /** Force database to use table "franchise_users".  */
    protected $table = 'franchise_users';

    public static function boot()
    {
        parent::boot();

        FranchiseUser::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class FranchiseAssignment extends Eloquent 
{
    protected $table = 'franchise_assignments';
    public static function boot()
    {
        parent::boot();

        FranchiseAssignment::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
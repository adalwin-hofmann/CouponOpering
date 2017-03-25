<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class UserAssignmentType extends Eloquent 
{
    /** Force database to use table "user_assignment_types".  */
    protected $table = 'user_assignment_types';

    public static function boot()
    {
        parent::boot();

        UserAssignmentType::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}

<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleCommandHistory extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_command_history';

    public static function boot()
    {
        parent::boot();

        VehicleCommandHistory::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
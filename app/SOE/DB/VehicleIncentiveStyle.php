<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleIncentiveStyle extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_incentive_styles';

    public static function boot()
    {
        parent::boot();

        VehicleIncentiveStyle::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}
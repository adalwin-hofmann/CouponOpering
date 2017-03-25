<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleIncentive extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_incentives';

    public static function boot()
    {
        parent::boot();

        VehicleIncentive::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    public function styles()
    {
        return $this->belongsToMany('\SOE\DB\VehicleStyle', 'vehicle_incentive_styles');
    }
}
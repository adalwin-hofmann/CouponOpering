<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class LocationHour extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        LocationHour::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /**
     * A locationhour belongs to a single Location
     *
     * @return Merchant
     */
    public function location()
    {
        return $this->belongsTo('Location');
    }

}
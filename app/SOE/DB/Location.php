<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Location extends Eloquent 
{
    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        Location::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

	/**
     * A location belongs to a single Merchant
     *
     * @return Merchant
     */
    public function merchant()
    {
        return $this->belongsTo('SOE\DB\Merchant');
    }

    /**
     * A location has many LocationHours
     *
     * @return LocationHours
     */
    public function hours()
    {
        return $this->hasMany('LocationHour');
    }

}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleMake extends Eloquent 
{
    /** Force database to use table "vehicle_makes".  */
    protected $table = 'vehicle_makes';

    public static function boot()
    {
        parent::boot();

        VehicleMake::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /**
     * Vehicle makes can belong to many merchants.
     *
     * @return array
     */
    public function merchants()
    {
        return $this->belongsToMany('\SOE\DB\Merchant', 'dealer_brands', 'make_id', 'merchant_id');
    }
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Merchant extends Eloquent 
{
    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        Merchant::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /**
     * Merchant belongs to a Company
     *
     * @return Company
     */
    public function company()
    {
        return $this->belongsTo('Company');
    }

    /**
     * Merchant can have a category
     *
     * @return Category
     */
    public function category()
    {
        return $this->belongsTo('\SOE\DB\Category');
    }

    /**
     * Merchant can have a parent category
     *
     * @return Category
     */
    public function subcategory()
    {
        return $this->belongsTo('\SOE\DB\Category', 'subcategory_id');
    }

    /**
     * Merchant can have many assets.
     *
     * @return Asset
     */
    public function assets()
    {
        return $this->morphMany('\SOE\DB\Asset', 'assetable');
    }

    public function eagerAssets()
    {
        return $this->hasMany('\SOE\DB\Asset', 'assetable_id')
                    ->where('assets.assetable_type', 'Merchant');
    }

    /**
     * Merchant can have many offers.
     *
     * @return Offer
     */
    public function offers()
    {
        return $this->hasMany('Offer');
    }

    /**
     * Merchant can have many banners.
     *
     * @return Banner
     */
    public function banners()
    {
        return $this->hasMany('Banner');
    }

    /**
     * Merchants can have many locations
     *
     * @return Location
     */
    public function locations()
    {
        return $this->hasMany('\SOE\DB\Location');
    }

    /**
     * Users can access this merchant
     *
     * @return User
     */
    public function users()
    {
        return $this->belongsToMany('User');
    }
    
    /**
     * Merchants can be viewed by users
     *
     * @return User
     */
    public function viewed_by_users()
    {
        return $this->belongsToMany('User', 'user_view');
    }

    /**
     * Merchants can have many UsedVehicle
     *
     * @return array
     */
    public function usedVehicles()
    {
        return $this->hasMany('\SOE\DB\UsedVehicle');
    }

    /**
     * Merchants can have many makes.
     *
     * @return array
     */
    public function makes()
    {
        return $this->belongsToMany('\SOE\DB\VehicleMake', 'dealer_brands', 'merchant_id', 'make_id');
    }

    public function franchises()
    {
        return $this->hasMany('\SOE\DB\Franchise');
    }

}
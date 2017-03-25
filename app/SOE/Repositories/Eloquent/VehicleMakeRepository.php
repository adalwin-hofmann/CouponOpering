<?php namespace SOE\Repositories\Eloquent;

class VehicleMakeRepository extends BaseRepository implements \VehicleMakeRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'edmunds_id',
        'is_active',
        'old_id',
    );

    protected $model = 'VehicleMake';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByName($slug)
    {
        return \SOE\DB\VehicleMake::where('slug', '=', $slug)->first();
    }

    public function getMerchantMakes(\SOE\DB\Merchant $merchant)
    {
        return \SOE\DB\VehicleMake::join('dealer_brands', 'vehicle_makes.id', '=', 'dealer_brands.make_id')
                                ->where('dealer_brands.merchant_id', '=', $merchant->id)
                                ->get(array('vehicle_makes.*'));
    }

    public function getActiveMakes()
    {
        return $this->query()
                    ->where('is_active', '=', '1')
                    ->orderBy('name')
                    ->get();
    }
    
}


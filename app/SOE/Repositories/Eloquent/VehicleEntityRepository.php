<?php namespace SOE\Repositories\Eloquent;

class VehicleEntityRepository extends BaseRepository implements \VehicleEntityRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'vendor',
        'vendor_dealer_id',
        'vendor_dealer_key',
        'vendor_inventory_id',
        'condition',
        'stock_number',
        'vin',
        'year',
        'model_year_id',
        'make_id',
        'make',
        'model_id',
        'model',
        'is_certified',
        'price',
        'mileage',
        'body_type',
        'vehicle_type',
        'class',
        'fuel',
        'engine',
        'cylinders',
        'transmission',
        'drive_type',
        'trim_level',
        'exterior_color',
        'interior_color',
        'city_mpg',
        'highway_mpg',
        'options',
        'dealer_comments',
        'msrp',
        'internet_price',
        'image_count',
        'image_urls',
        'display_image',
        'dealer_name',
        'dealer_slug',
        'merchant_id',
        'location_id',
        'address',
        'city',
        'state',
        'zipcode',
        'phone',
        'latitude',
        'longitude',
        'latm',
        'lngm',
        'is_prequalified',
        'dealer_radius',
        'web_payout_price',
        'phone_extension',
        'phone_payout_price',
        'ppc_url',
        'ppc_payout',
    );

    protected $model = 'VehicleEntity';

    public function __construct()
    {
        $this->zipcodes = \App::make('ZipcodeRepositoryInterface');
        $this->defaultConn = 'mysql-affiliate';
        parent::__construct();
    }

    public function query()
    {
        return \SOE\DB\VehicleEntity::on($this->defaultConn);
    }

    public function with($relations) {
        return \SOE\DB\VehicleEntity::on($this->defaultConn)->with($relations);
    }

    /**
     * Retrieve a vehicle by vendor and vendor inventory id.
     *
     * @param string $vendor
     * @param integer $inventory_id
     * @return mixed
     */
    public function findByVendorInventoryId($vendor, $inventory_id)
    {
        return $this->query()
            ->where('vendor', $vendor)
            ->where('vendor_inventory_id', $inventory_id)
            ->first();
    }

    public function findByVinAndVendor($vin, $vendor)
    {
        $vehicle = $this->query()
            ->where('vehicle_entities.vin', $vin)
            ->where('vendor', $vendor)
            ->first();

        if(!$vehicle)
            return $vehicle;

        $franchise = \SOE\DB\Franchise::where('merchant_id', $vehicle->merchant_id)->first();
        $vehicle->netlms_id = $franchise ? $franchise->netlms_id : 0;
        return $vehicle;
    }

    public function getByVendor($vendor, $ids_only = false, $page = 0, $limit = 0)
    {
        $query = $this->query()
            ->where('vendor', $vendor);
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($page*$limit);
        }

        $get = $ids_only ? array('id', 'vendor_inventory_id') : array('*');
        $results = $query->get($get);
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    public function touchRemaining($aRemaining)
    {
        $batches = array();
        $batch = array();
        $total = 0;
        for($i=0; $i<count($aRemaining); $i++)
        {
            $batch[] = $aRemaining[$i];
            $total++;
            if($total >= 500)
            {
                $batches[] = $batch;
                $batch = array();
                $total = 0;
            }
        }

        foreach($batches as $batch)
        {
            $this->query()
                ->whereIn('id', $batch)
                ->update(array('updated_at' => \DB::raw('NOW()')));
        }
    }

    /**
     * Delete all outdated inventory for vendor.
     *
     * @param string $vendor
     * @param sting $updated_at Y-m-d H:i:s
     * @return void
     */
    public function deleteOldForVendor($vendor, $updated_at)
    {
        $this->query()
            ->where('vendor', $vendor)
            ->where('updated_at', '<', $updated_at)
            ->delete();
    }

    /**
     * Get vehicles for the given merchant.
     *
     * @param int $merchant_id
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getByMerchant($merchant_id, $page = 0, $limit = 0)
    {
        $query = $this->query()
                    ->where('merchant_id', '=', $merchant_id)
                    ->orderBy('make')
                    ->orderBy('model');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        foreach($results as &$result)
        {
            $merchant = \SOE\DB\Merchant::on('mysql-write')->find($result->merchant_id);
            $result->merchant = $merchant;
        }
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    /**
     * Get vehicles matching the search params.
     *
     * @param int $year
     * @param int $make     Make Id
     * @param int $model    Model Id
     * @param int $min      Minimum search price.
     * @param int $max      Maximum search price.
     * @param int $dist     Distance in miles
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function search(
        $year = null, $make = null, $model = null, 
        $min = null, $max = null, $dist = null, 
        $page = 0, $limit = 0, $order = 'dist', 
        $mileage_limit = null, $body_style = null,
        $state = null, $with_stats = true, $filter_city = true
    )
    {
        $query = $this->query()->select(array('*'));
        $query->where('internet_price', '>', '500')
            ->where('condition', 'pre-owned')
            ->where('image_count', '!=', 0);
        if($year)
            $query->years($year);
        if($make)
            $query->makes($make);
        if($model)
            $query->models($model);
        if($min || $max)
            $query->price($min, $max);
        if($dist)
            $query->distance($dist);
        if($mileage_limit)
            $query->where('mileage', '<=', $mileage_limit)->where('mileage', '!=', '0');
        if(($body_style) && $body_style != 'all')
        {
            $styles = explode(',', $body_style);
            $query->where(function($query) use($styles)
                {
                    $query->where('body_type', 'LIKE', '%'.$styles[0].'%');
                    for($i=1; $i<count($styles); $i++)
                    {
                        $query->orWhere('body_type', 'LIKE', '%'.$styles[$i].'%');
                    }
                });
        }
        if($state)
            $query->where('state', strtoupper($state));
        switch ($order)
        {
            case 'rand':
                $query->where('id', '>=', \DB::raw("FLOOR(1 + RAND() * (SELECT MAX(id) FROM vehicle_entities))"))->orderBy('id');
                $query->withDistance();
                break;
                
            default:
                $geoip = json_decode(\GeoIp::getGeoIp('json'));
                $query->where('state', strtoupper($geoip->region_name));
                
                if(!$filter_city)
                {
                    $query->distance(50, $geoip->latitude, $geoip->longitude);
                }
                else
                {
                    $query->where('city', $geoip->city_name);
                    $query->orderByDistance();
                }
                break;
        }

        if($with_stats)
            $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $get = array();
        $get[] = 'vehicle_entities.*';
        //$get[] = \DB::raw("IF(image_count != 0, 1, 0) as has_img");
        //$query = $query->orderBy(\DB::raw("IF(image_count != 0, 1, 0)"),'desc');
        $results = $query->get();
        $personFactory = new \SOE\Persons\PersonFactory;
        $person = $personFactory->make();
        foreach($results as &$vehicleEntity)
        {
            $favoritable = new \SOE\Favoritables\VehicleEntityFavoritable($vehicleEntity->id);
            $favorite = $person ? $favoritable->isFavorite($person) : false;
            $vehicleEntity->is_saved = $favorite ? 1 : 0;
            $merchant = \SOE\DB\Merchant::on('mysql-write')->find($vehicleEntity->merchant_id);
            $vehicleEntity->merchant = $merchant;
        }
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    /**
     * Get used vehicles related the search params.
     *
     * @param int $year
     * @param int $make     Make Id
     * @param int $model    Model Id
     * @param int $min      Minimum search price.
     * @param int $max      Maximum search price.
     * @param int $dist     Distance in miles
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchRelated($year = null, $make = null, $model = null, $min = null, $max = null, $dist = null, $page = 0, $limit = 0)
    {
        $query = $this->with('merchant'/*,'merchant.locations'*/)->select(array('*'));
        if(!$year)
            $year = date('Y');
        if($make)
            $query->makes($make);
        if($model)
            $query->models($model);
        $query->where('id', '>=', \DB::raw("FLOOR(1 + RAND() * (SELECT MAX(id) FROM vehicle_entities))"))->orderBy('id');
        $query->withDistance();
        $query->orderBy(\DB::raw('abs(vehicle_entities.year -'.$year.')'));

        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    public function BrokenVehicleImage($image, $vehicle_id)
    {
        echo $image;
        $query = $this->query()->where('id', $vehicle_id)->first();
        $images = str_replace('||','|',$query->image_urls);
        $imagesArray = explode('|',$images);
        if (in_array($image, $imagesArray))
        {
            // Search
            $pos = array_search($image, $imagesArray);
            // Remove from array
            unset($imagesArray[$pos]);
            $images = implode('|',$imagesArray);
            $query->image_urls = $images;
            $query->save();
        }
        
        if ($query->display_image == $image)
        {
            if (count($imagesArray) > 1)
            {
                $query->display_image = $imagesArray[1];
            } else {
                $query->display_image = '';
            }
            $query->save();
        }
        //return 'Removed an image for '.$vehicle_id;
        return $query;
    }

    public function getFeaturedVehicle($latitude, $longitude, $state)
    {
        $featured = $this->query()
            ->where('vehicle_entities.condition', 'pre-owned')
            ->where('vehicle_entities.image_count', '!=', 0)
            ->where('vehicle_entities.internet_price', '>', 500)
            ->where('state', strtoupper($state))
            ->distance(25, $latitude, $longitude)
            ->orderBy(\DB::raw("RAND()"))
            ->first();
        return $featured;
    }

    public function rssQuery($min_price = null, $max_price = null, $year = null, $make = null, $model = null, $trim = null, $zipcode = null, $radius = 75, $vin = null, $description = 0, $images = 0, $merchant_id = null, $page = 0, $limit = 0, $state = null, $include_new = 'no')
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $query = $this->query()
            ->where('vehicle_entities.image_count', '!=', 0)
            ->where('vehicle_entities.internet_price', '>', 500)
            ->where('vehicle_entities.vendor', 'detroit_trading')
            ->where('vehicle_entities.dealer_name', '!=', 'WHOLESALE PARTNER');
        switch ($include_new) {
            case 'yes':
                $query = $query;
                break;
            case 'only':
                $query->where('vehicle_entities.condition', 'new');
                break;
            default:
                $query->where('vehicle_entities.condition', 'pre-owned');
                break;
        }
        if($min_price)
            $query->where('vehicle_entities.internet_price', '>', $min_price);
        if($max_price)
            $query->where('vehicle_entities.internet_price', '<', $max_price);
        if($year)
            $query->where('vehicle_entities.year', $year);
        if($make)
            $query->where('make', $make);
        if($model)
            $query->where('vehicle_entities.model', 'LIKE', \DB::raw("'%".$model."%'"));
        if($trim)
            $query->where('vehicle_entities.trim_level', 'LIKE', \DB::raw("'%".$trim."%'"));
        if($zipcode)
        {
            $zip = $this->zipcodes->findByZipcode($zipcode);
            if($zip)
            {
                $query->distance($radius > 75 ? 75 : $radius, $zip->latitude, $zip->longitude);
                $query->where('state', $zip->state);
            }
        }
        if($vin)
            $query->where('vehicle_entities.vin', $vin);
        if($merchant_id)
            $query->where('vehicle_entities.merchant_id', $merchant_id);
        if($state)
            $query->where('vehicle_entities.state', $state);
        $get = array(
            'vehicle_entities.id',
            'vehicle_entities.address',
            'vehicle_entities.city',
            'vehicle_entities.state',
            'vehicle_entities.zipcode',
            'vehicle_entities.display_image',
            'vehicle_entities.internet_price',
            'vehicle_entities.vehicle_type',
            'vehicle_entities.year',
            'vehicle_entities.make',
            'vehicle_entities.model',
            'vehicle_entities.trim_level',
            'vehicle_entities.vin',
            'vehicle_entities.dealer_name',
            'vehicle_entities.vendor_inventory_id',
            'vehicle_entities.mileage',
            'vehicle_entities.merchant_id',
            'vehicle_entities.condition'
        );
        if($description != 0)
        {
            $get[] = 'vehicle_entities.options';
            $get[] = 'vehicle_entities.dealer_comments';
        }
        if($images != 0)
        {
            $get[] = 'vehicle_entities.image_urls';
        }

        //$stats = $this->getStats(clone $query, $limit, $page, true);
        if($limit)
        {
            $query->skip($page*$limit)->take($limit);
        }
        $results['objects'] = $query->get($get);

        $stats = array('stats' => array('returned' => count($results['objects'])));
        return array_merge($results, $stats);
    }
}
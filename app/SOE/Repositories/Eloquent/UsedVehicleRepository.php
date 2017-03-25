<?php namespace SOE\Repositories\Eloquent;

class UsedVehicleRepository extends BaseRepository implements \UsedVehicleRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'tnl_id',
        'dealer_name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'latitude',
        'longitude',
        'stock_number',
        'vin',
        'year',
        'model_year_id',
        'make',
        'make_id',
        'model_id',
        'model',
        'mileage',
        'is_certified',
        'class',
        'body_style',
        'fuel',
        'engine',
        'cylinders',
        'transmission',
        'drive_type',
        'trim_level',
        'exterior_color',
        'interior_color',
        'dealer_specified_features',
        'standard_interior_features',
        'standard_exterior_features',
        'standard_safety_features',
        'dealer_comments',
        'msrp',
        'internet_price',
        'image_count',
        'image_urls',
        'merchant_id',
        'location_id',
        'latm',
        'lngm',
        'old_dealer_id',
        'vendor',
        'standard_mechanical_features',
        'name',
        'style_id',
        'is_active',
    );

    protected $model = 'UsedVehicle';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get used vehicles for the given merchant.
     *
     * @param int $merchant_id
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getByMerchant($merchant_id, $page = 0, $limit = 0)
    {
        $query = $this->with('merchant')
                    ->where('merchant_id', '=', $merchant_id)
                    ->where('is_active', '1')
                    ->orderBy('make')
                    ->orderBy('model');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    /**
     * Get used vehicles matching the search params.
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
        $state = null
    )
    {
        $query = $this->with('merchant'/*,'merchant.locations'*/)->select(array('*'));
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
        if($body_style)
        {
            $styles = explode(',', $body_style);
            $query->where(function($query) use($styles)
                {
                    $query->where('body_style', 'LIKE', '%'.$styles[0].'%');
                    for($i=1; $i<count($styles); $i++)
                    {
                        $query->orWhere('body_style', 'LIKE', '%'.$styles[$i].'%');
                    }
                });
        }
        if($state)
            $query->where('state','=',$state);
        switch ($order)
        {
            case 'rand':
                $query->orderBy(\DB::raw('RAND()'));
                break;
            
            default:
                $query->orderByDistance();
                break;
        }
        $query->where('is_active', '1');
        //Make sure it has an image
        $query->where('image_urls','!=','');

        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $get = array();
        $get[] = 'used_vehicles.*';
        $get[] = \DB::raw("IF(image_urls != '', 1, 0) as has_img");
        $query = $query->orderBy(\DB::raw("IF(image_urls != '', 1, 0)"),'desc');
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    /**
     * Get used vehicles matching the search params.
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
        $query->where('is_active', '1')->orderByDistance();
        $query->orderBy(\DB::raw('abs(used_vehicles.year -'.$year.')'));

        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    public function getActive($page = 0, $limit = 0)
    {
        $query = $this->query()->where('is_active', '1');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query = $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }
    
}


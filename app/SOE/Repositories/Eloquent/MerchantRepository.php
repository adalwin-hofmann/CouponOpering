<?php namespace SOE\Repositories\Eloquent;

class MerchantRepository extends BaseRepository implements \MerchantRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'display',
        'slug',
        'type',
        'about',
        'catchphrase',
        'facebook',
        'twitter',
        'website',
        'hours',
        'phone',
        'category_id',
        'subcategory_id',
        'default_location_id',
        'yipitbusiness_id',
        'primary_contact',
        'coupon_tab_type',
        'is_demo',
        'is_active',
        'created_by',
        'updated_by',
        'keywords',
        'max_prints',
        'mobile_redemption',
        'rating',
        'is_displayed',
        'page_title',
        'meta_description',
        'tnl_id',
        'old_id',
        'vendor',
        'new_disclaimer',
        'used_disclaimer',
        'service_radius',
        'page_version',
        'sub_heading',
        'offer_keywords',
        'entity_search_parse',
        'is_offer_notifications',
    );

    protected $model = 'Merchant';

    public function findBySlug($slug)
    {
        return $this->with('category', 'subcategory')
            ->where('slug', $slug)
            ->where('is_active', '1')
            ->where('is_displayed', '1')
            ->first();
    }

    /**
     * Retrieve entities belonging to this location.
     *
     * @param  SOE\DB\Location      $location
     * @param  boolean              $show_demo
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Entities.
     */
    public function getEntities($merchant_id, $show_demo = false, $page = 0, $limit = 12, $loc_ids = array(), $order = 'rand')
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Entity::join('locations', 'entities.location_id', '=', 'locations.id')
                                ->where('entities.merchant_id', '=', $merchant_id)
                                ->where('entities.is_active', '=', '1');

        $query = $query->where(function($query)
        {
            $query->where('starts_year', '=', date('Y'));
            $query->where('starts_day', '<=', (date('z')+1));
            $query->orWhere('starts_year', '<', (date('Y')));
        })
        ->where(function($query)
        {
            $query->where('expires_year', '=', date('Y'));
            $query->where('expires_day', '>=', (date('z')+1));
            $query->orWhere('expires_year', '>=', (date('Y')+1));
        });
        if($show_demo == false)
        {
            $query = $query->where('entities.is_demo', '=', 0);
        }
        if(!empty($loc_ids) && is_array($loc_ids))
        {
            $query->whereIn('entities.location_id', $loc_ids);
        }
        $query->groupBy('entities.entitiable_id');
        $query->groupBy('entities.entitiable_type');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        switch ($order) {
            case 'importance':
                $query = $query->orderBy('importance');
                break;
            case 'distance':
                $query = $query->orderBy($distance, 'asc');
                break;
            default:
                $query = $query->orderBy(\DB::raw('RAND()'));
                break;
        }
        $entities = $query->get(array('entities.*', 'locations.city', \DB::raw("IF(is_dailydeal = 1, 0, IF(entities.entitiable_type = 'Contest', 1, 2)) as importance")));

        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        foreach($entities as &$entity)
        {
            $entity->is_clipped = 0;

            if($entity->entitiable_type == 'Contest')
            {
                $contest = \SOE\DB\Contest::where('id', $entity->entitiable_id)->remember(\Config::get('soe.cache', 60*24))->first(array('id', 'display_name'));
                $entity->display_name = $contest->display_name;
            }

            $results['objects'][] = $entity;
        }
        
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Find the nearest Location with the given merchant slug.
     *
     * @param string    $slug The merchant slug.
     * @param float     $latitude The latitude to search by.
     * @param float     $longitude The longitude to search by.
     * 
     * @return array Location
     */
    public function findNearestLocationBySlug($slug, $latitude, $longitude)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                ->where('merchants.slug', '=', $slug)
                                ->where('merchants.is_active', '=', '1')
                                ->where('locations.is_active', '=', '1')
                                ->orderBy($distance);
        $users = \App::make('UserRepositoryInterface');
        $showDemo = \Auth::check() ? $users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('merchants.is_demo', '=', '0');
        }

        $location = $query->remember(600)->first(array('locations.*'));

        return $location;
    }

    /**
     * Find the nearest Location with the given merchant id.
     *
     * @param int       $id The merchant id.
     * @param float     $latitude The latitude to search by.
     * @param float     $longitude The longitude to search by.
     * 
     * @return array Location
     */
    public function findNearestLocationById($id, $latitude, $longitude)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                ->where('merchants.id', '=', $id)
                                ->where('merchants.is_active', '=', '1')
                                ->where('locations.is_active', '=', '1')
                                ->orderBy($distance);
        $users = \App::make('UserRepositoryInterface');
        $showDemo = \Auth::check() ? $users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('merchants.is_demo', '=', '0');
        }

        $location = $query->remember(600)->first(array('locations.*'));

        return $location;
    }

    /**
     * Find the nearest Location with the given merchant slug.
     *
     * @param string    $slug The merchant slug.
     * @param float     $latitude The latitude to search by.
     * @param float     $longitude The longitude to search by.
     * 
     * @return array Location
     */
    public function findNearestLocationBySlugWithin50($slug, $latitude, $longitude)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                ->where('merchants.slug', '=', $slug)
                                ->where('merchants.is_active', '=', '1')
                                ->where('locations.is_active', '=', '1')
                                ->orderBy($distance);
        $users = \App::make('UserRepositoryInterface');
        $showDemo = \Auth::check() ? $users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('merchants.is_demo', '=', '0');
        }
        $query = $query->where($distance, '<=', '100000'); //About 50 miles
        $location = $query->first(array('locations.*'));

        return $location;
    }

    /**
     * Find the nearest Location with the given merchant id.
     *
     * @param integer    $merchantId The merchant id.
     * @param float     $latitude The latitude to search by.
     * @param float     $longitude The longitude to search by.
     * 
     * @return array Location
     */
    public function findNearestLocationByMerchantIdWithin50($merchantId, $latitude, $longitude)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                ->where('merchants.id', '=', $merchantId)
                                ->where('merchants.is_active', '=', '1')
                                ->where('locations.is_active', '=', '1')
                                ->orderBy($distance);
        $users = \App::make('UserRepositoryInterface');
        $showDemo = \Auth::check() ? $users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('merchants.is_demo', '=', '0');
        }
        $query = $query->where($distance, '<=', '100000'); //About 50 miles
        $location = $query->first(array('locations.*'));

        return $location;
    }

    /**
     * Get the total number of active locations for this merchant.
     *
     * @param object $merchant
     * @return int
     */
    public function locationsCount($merchant)
    {
        return \SOE\DB\Location::where('merchant_id', $merchant->id)
                                ->where('is_active', '1')
                                ->count();
    }

    /**
     * Find a merchant by white label company id and old merchant id.
     *
     * @param int $company_id
     * @param int $merchant_id
     * @return mixed
     */
    public function findWhitelabel($company_id, $merchant_id)
    {
        return $this->query()->join('franchises', 'franchises.merchant_id', '=', 'merchants.id')
                            ->where('merchants.old_id', $merchant_id)
                            ->where('franchises.company_id', $company_id)
                            ->first(array('merchants.*'));
    }

    /**
     * Find a merchant by old id and vendor name.
     *
     * @param integer $old_id
     * @param string $vendor
     * @return mixed
     */
    public function findByOldIdVendor($old_id, $vendor)
    {
        return $this->query()
            ->where('old_id', $old_id)
            ->where('vendor', $vendor)
            ->first();
    }

    /**
     * Get all merchants belonging to a vendor.
     *
     * @param string $vendor
     * @return mixed
     */
    public function getByVendor($vendor)
    {
        return $this->query()
            ->where('vendor', $vendor)
            ->get(array('display', 'slug', 'id', 'old_id'));
    }

    public function getActive($vendor = null, $withYipit = true, $market = null)
    {
        $query = $this->query()
            ->where('merchants.is_active', '1')
            ->where('merchants.is_demo', '0');
        if($vendor)
            $query->where('merchants.vendor', $vendor);
        else
            $query->where('merchants.vendor', '!=', 'detroit_trading');

        if(!$withYipit)
            $query->where('merchants.yipitbusiness_id', 0);

        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $merchants = $query->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                    ->where('locations.state', $abbr)
                    ->groupBy('merchants.id')
                    ->get(array('merchants.id'));
                return count($merchants);
            }
        }
        
        return $query->count();
    }
}
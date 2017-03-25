<?php namespace SOE\Repositories\Eloquent;

class LocationRepository extends BaseRepository implements \LocationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'is_demo',
        'is_active',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'hours',
        'phone',
        'website',
        'rating',
        'rating_count',
        'merchant_id',
        'division_id',
        'franchise_id',
        'company_id',
        'is_national',
        'created_by',
        'updated_by',
        'latm',
        'lngm',
        'merchant_name',
        'merchant_slug',
        'deleted_at',
        'about',
        'page_title',
        'keywords',
        'meta_description',
        'old_id',
        'custom_website',
        'custom_website_text',
        'subheader',
        'redirect_number',
        'redirect_text',
        'display_name',
        'is_logo_specific',
        'is_banner_specific',
        'is_about_specific',
        'is_pdf_specific',
        'is_video_specific',
        'facebook',
        'twitter',
        'is_address_hidden',
        'custom_address_text',
        'is_24_hours'
    );

    protected $model = 'Location';

    public function __construct()
    {
        $this->users = \App::make('UserRepositoryInterface');
        $this->zipcodes = \App::make('ZipcodeRepositoryInterface');
        parent::__construct();
    }

    /**
     * Get locations by for the given franchise
     *
     * @param SOE\DB\Franchise   $franchise
     * @param int   $page
     * @param int   $limit
     * @return mixed
     */
    public function getByFranchise(\SOE\DB\Franchise $franchise, $page = 0, $limit = 0)
    {
        $locations = \SOE\DB\Location::where('franchise_id', '=', $franchise->id)->orderBy('name', 'asc');
        $stats = $this->getStats(clone $locations, $limit, $page);
        if($limit)
        {
            $locations = $locations->take($limit)->skip($limit*$page);
        }
        return $locations->get();
    }

    public function getActiveByFranchise($franchise_id, $page = 0, $limit = 0)
    {
        $locations = \SOE\DB\Location::where('franchise_id', '=', $franchise_id)
            ->where('is_national', '0')
            ->where('is_active', '1')
            ->where('is_demo', '0')
            ->orderBy('name', 'asc');
        $stats = $this->getStats(clone $locations, $limit, $page);
        if($limit)
        {
            $locations = $locations->take($limit)->skip($limit*$page);
        }
        return $locations->get();
    }

    /**
     * Get the locations for a franchise, ordered by distance.
     *
     * @param int $franchise_id
     * @param float $latitude
     * @param float $longitude
     * @param int $page The page of search results, default 0.
     * @param int $limit The number of search results to return, default all.
     * 
     * @return mixed
     */
    public function getFranchiseLocationsByDistance($franchise_id, $latitude, $longitude, $page = 0, $limit = 0)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::where('franchise_id', '=', $franchise_id)
                                ->where('is_national', '=', '0')
                                ->where('is_active', '=', '1');
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('is_demo', '=', '0');
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $locations = $query->orderBy($distance)->remember(Config::get('soe.cache', 60*60*24))->get();
        $stats['stats']['returned'] = count($locations);
        $results = array('objects' => $locations);

        return array_merge($results, $stats);
    }

    public function getSponsorLocations($latitude, $longitude, $level = null, $page = 0, $limit = 0)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::join('franchises', 'locations.franchise_id', '=', 'franchises.id')
            ->where('locations.is_national', '=', '0')
            ->where('locations.is_active', '=', '1')
            ->where('franchises.is_active', '=', '1');
        if($level)
        {
            $query->where('franchises.sponsor_level', $level);
        }
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('locations.is_demo', '=', '0');
        }
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $locations = $query->orderBy($distance)
            ->where($distance, '<=', '60000') //About 30 miles
            ->groupBy('franchises.id')
            /*->remember(Config::get('soe.cache', 60*60*24))*/->get();

        return $locations;
    }

    public function getSponsorBanner($zipcode, $level = null)
    {
        $zipcode = $this->zipcodes->findByZipcode($zipcode);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$zipcode->latm.', 2) + pow(locations.lngm - '.$zipcode->lngm.', 2)))');
        $query = \SOE\DB\Location::join('franchises', 'locations.franchise_id', '=', 'franchises.id')
            ->where('locations.is_national', '=', '0')
            ->where('locations.is_active', '=', '1')
            ->where('franchises.is_active', '=', '1')
            ->where('franchises.sponsor_banner', '!=', '')
            ->whereNotNull('franchises.sponsor_banner')
            ->join('merchants', 'locations.merchant_id', '=', 'merchants.id')
            ->join('categories as cat', 'cat.id', '=', 'merchants.category_id')
            ->join('categories as subcat', 'subcat.id', '=', 'merchants.subcategory_id');
        if($level)
        {
            $query->where('franchises.sponsor_level', $level);
        }
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('locations.is_demo', '=', '0');
        }
        $banner = $query->where($distance, '<=', '60000') //About 30 miles
            ->groupBy('franchises.id')
            ->orderBy(\DB::raw('RAND()'))
            ->first(array('locations.*', 'franchises.sponsor_banner', \DB::raw('cat.slug as cat_slug'), \DB::raw('subcat.slug as subcat_slug')));

        return $banner;
    }

    /**
     * Get the locations for a merchant, ordered by distance.
     *
     * @param int $merchant_id
     * @param float $latitude
     * @param float $longitude
     * @param int $page The page of search results, default 0.
     * @param int $limit The number of search results to return, default all.
     * 
     * @return mixed
     */
    public function getMerchantLocationsByDistance($merchant_id, $latitude, $longitude, $page = 0, $limit = 0)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = \SOE\DB\Location::where('merchant_id', '=', $merchant_id)
                                ->where('is_national', '=', '0')
                                ->where('is_active', '=', '1');
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('is_demo', '=', '0');
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $locations = $query->orderBy($distance)->remember(\Config::get('soe.cache', 60*60*24))->get();
        $stats['stats']['returned'] = count($locations);
        $results = array('objects' => $locations);

        return array_merge($results, $stats);
    }

    public function getRecentlyUpdatedByMerchant($merchant_id, $page = 0, $limit = 0)
    {
        $query = \SOE\DB\Location::where('merchant_id', '=', $merchant_id)
                                ->where('is_national', '=', '0')
                                ->where('is_active', '=', '1');
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('is_demo', '=', '0');
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $locations = $query->get();
        $stats['stats']['returned'] = count($locations);
        $results = array('objects' => $locations);

        return array_merge($results, $stats);
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
    public function getEntities(\SOE\DB\Location $location, $show_demo = false, $page = 0, $limit = 12, $expired = false)
    {
        $query = \SOE\DB\Entity::where('state', '=', $location->state)
                                ->where('merchant_id', '=', $location->merchant_id)
                                ->where('location_id', '=', $location->id)
                                ->where('is_active', '=', '1');
        if($expired == false)
        {
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
        }
        if($show_demo == false)
        {
            $query = $query->where('is_demo', '=', 0);
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $query = $query->orderBy('importance');
        $entities = $query/*->remember(\Config::get('soe.cache', 60*60*24))*/->get(array('entities.*', \DB::raw("IF(is_dailydeal = 1, 0, IF(entitiable_type = 'Contest', 1, 2)) as importance")));

        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        $aContIDs = array(0);
        foreach($entities as $entity)
        {
            $aIDs[] = $entity->location_id;
            if($entity->entitiable_type == 'Contest')
            {
                $aContIDs[] = $entity->entitiable_id;
            }
        }
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->/*remember(\Config::get('soe.cache', 60*24))->*/get(array('id', 'display_name'));
        $aContNames = array();
        foreach($contests as $contest)
        {
            $aContNames[$contest->id] = $contest->display_name;
        }
        foreach($entities as &$entity)
        {
            $entity->is_clipped = 0;
            $entity->display_name = ($entity->entitiable_type == 'Contest' && isset($aContNames[$entity->entitiable_id])) ? $aContNames[$entity->entitiable_id] : '';
            $results['objects'][] = $entity;
        }
        
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Get Featured Offer On Location
     *
     * @param object $location
     * @return void
     */
    public function getFeaturedByLocation($location, $limitedTime = false)
    {
        $query = \SOE\DB\Entity::where('state', '=', $location->state)
            ->where('merchant_id', '=', $location->merchant_id)
            ->where('location_id', '=', $location->id)
            ->where('is_active', '=', '1')
            ->where('is_featured', '=', '1');
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
        if($limitedTime)
        {
            $twoWeeks = strtotime('+2 weeks');
            $query = $query->where(function($query) use ($twoWeeks)
            {
                $query->whereBetween('expires_day', array((date('z')),(date('z',$twoWeeks))));
            });
        }
        $query = $query->first();
        return $query;
    }

    /**
     * Retrieve review belonging to this location.
     *
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Reviews.
     */
    public function getReviews($location, $page = 0, $limit = 12)
    {
        $query = \SOE\DB\Review::where('reviewable_id', '=', $location->id)
                            ->where('reviewable_type', '=', 'Location')
                            ->where('is_deleted', '=', '0')
                            ->orderBy('upvotes', 'desc')
                            ->orderBy('created_at', 'desc');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $reviews = $query->get();
        $stats['stats']['returned'] = count($reviews);
        $results = array('objects' => array());
        foreach($reviews as $review)
        {
            $upvotes = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '1')->count();
            $downvotes = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '-1')->count();
            $myvote = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0);
            if(\Auth::Check())
            {
                $myvote = $myvote->where('user_id', '=', \Auth::User()->id);
            }
            else
            {
                $myvote = $myvote->where('nonmember_id', '=', \Auth::Nonmember()->id);
            }
            $review->votes = $upvotes + $downvotes;
            $review->upvotes = $upvotes;
            $myvote = $myvote->first();
            $review->my_vote = empty($myvote) ? 0 : $myvote->vote;
            $review->user = \SOE\DB\User::find($review->user_id);
            $results['objects'][] = $review;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Retrieve hours belonging to this location.
     *
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Hours.
     */
    public function getHours($location)
    {
        $query = \SOE\DB\LocationHour::where('location_id', '=', $location->id)->get();
        return $query;
    }

    public function view($location, \PersonInterface $viewer)
    {
        $location_id = $location->id;
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
        $viewer_id = $viewer->id;
        $viewer_type = $viewer->getType();
        $tracking_id = \Cookie::get('tracking_id');
        $url = \Cookie::get('tracking_url');
        $refer_id = \Cookie::get('tracking_referid');
        if(\App::environment() == 'prod') {
            \Queue::push(function($job) use ($viewer_id, $viewer_type, $location_id, $geoip, $user_agent, $tracking_id, $url, $refer_id)
            {
                $viewer = $viewer_type == 'User' ? \SOE\DB\User::find($viewer_id) : \SOE\DB\Nonmember::find($viewer_id);
                if(\SoeHelper::isBot())
                {
                    $job->delete();
                    return;
                }
                if(!$viewer)
                {
                    $log = new \SOE\DB\SysLog;
                    $log->type = 'No Viewer Error';
                    $log->message = 'User Agent: '.(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
                    $log->save();
                    $job->delete();
                    return;
                }
                $location = \SOE\DB\Location::find($location_id);
                $merchant = \SOE\DB\Merchant::find($location->merchant_id);
                $category = \SOE\DB\Category::find($merchant->category_id);
                $subcategory = \SOE\DB\Category::find($merchant->subcategory_id);
                $company = \SOE\DB\Company::where('id','=',$location->company_id)->first();

                $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                $mp->identify($viewer->getType() == 'User' ? $viewer->email : 'non-'.$viewer->id);
                $mp->track('Location View', array(
                    '$city' => $geoip->city_name,
                    'Environment' => \App::environment(),
                    'FranchiseId' => $location->franchise_id,
                    'LocationId' => $location->id,
                    'MerchantId' => $location->merchant_id,
                    'MerchantName' => $location->merchant_name,
                    'MerchantNameAddress' => $location->merchant_name.' - '.$location->address,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => !empty($company) ? $company->id : '',
                    'CompanyName' => !empty($company) ? $company->name : '',
                    'UserType' => $viewer->getType()
                ));

                $view = new \SOE\DB\UserView;
                if($viewer->getType() == 'User')
                {
                    $view->user_id = $viewer->id;
                }
                else
                {
                    $view->nonmember_id = $viewer->id;
                }
                $view->merchant_id = $location->merchant_id;
                $view->franchise_id = $location->franchise_id;
                $view->location_id = $location->id;
                $view->user_agent = $user_agent;
                $view->tracking_id = $tracking_id;
                $view->url = $url;
                $view->refer_id = $refer_id;
                $view->save();
                $job->delete();
            });
        }
    }

    /**
     * Retrieve Cities Based on how many Merchant Locations are there
     *
     * 
     * @return array Reviews.
     */
    public function getTopCitiesByState($state, $page = 0, $limit = 20, $min_population = 15000, $group = true)
    {
        $cities = \SOE\DB\Location::where('city', '!=', "")
            ->where('state', '=', strtoupper($state));
        if($group)
        {
            $cities = $cities->groupBy('city');
        }

        $stats = $this->getStats(clone $cities, $limit, $page, true);
        $cities = $this->paginator($cities, $limit, $page);
        $cities = $cities->orderBy('location_count', 'desc')->having('location_count', '>', 50);
        $cities = $cities->get(array(\DB::raw('COUNT(*) as location_count'), 'city', 'state', 'zip', 'latitude', 'longitude'));
        $aCities = array();
        $results = array('objects' => array());
        foreach($cities as $city)
        { 
            $aCities[$city->city] = $city;
        }
        //Get the city with the most location
        $top_city = array('top_city' => count($cities) ? $cities[0] : array());
        
        ksort($aCities);

        foreach($aCities as $city)
        { 
            $results['objects'][] = $city; 
        }
        $stats['stats']['returned'] = count($cities);
        return array_merge($results, $stats, $top_city);
    }

    /**
     * Retrieve Cities Based on how many Merchant Locations are there
     *
     * 
     * @return array Reviews.
     */
    public function getTopCityByState($state, $page = 0, $limit = 1, $min_population = 15000, $group = true)
    {
        $cities = \SOE\DB\Location::where('city', '!=', "")
            ->where('state', '=', strtoupper($state));
        $cities = $cities->groupBy('city');

        $stats = $this->getStats(clone $cities, $limit, $page, true);
        $cities = $this->paginator($cities, $limit, $page);
        $cities = $cities->orderBy('location_count', 'desc')->having('location_count', '>', 50);
        $cities = $cities->get(array(\DB::raw('COUNT(*) as location_count'), 'city', 'state', 'zip', 'latitude', 'longitude'));
        $aCities = array();
        $results = array('objects' => array());
        foreach($cities as $city)
        { 
            $aCities[$city->city] = $city; 
        }
        //ksort($aCities);

        foreach($aCities as $city)
        { 
            $results['objects'][] = $city; 
        }
        $stats['stats']['returned'] = count($cities);
        return array_merge($results, $stats);
    }

    /**
     * Find a location by white label company id and old location id.
     *
     * @param int $company_id
     * @param int $location_id
     * @return mixed
     */
    public function findWhitelabel($company_id, $location_id)
    {
        return $this->query()->join('franchises', 'franchises.id', '=', 'locations.franchise_id')
                            ->where('locations.old_id', $location_id)
                            ->where('franchises.company_id', $company_id)
                            ->first(array('locations.*'));
    }

    /**
     * Update locations when a merchant is updated based on franchise and merchant.
     *
     * @param object $franchise
     * @param object $merchant
     * @return void
     */
    public function updateMerchant($franchise, $merchant)
    {
        $this->query()->where('franchise_id', $franchise->id)
                    ->update(array(
                        'merchant_name' => $merchant->display,
                        'merchant_slug' => $merchant->slug
                    ));
    }

    public function getByVendor($vendor)
    {
        return $this->query()->join('merchants', 'locations.merchant_id', '=', 'merchants.id')
            ->where('merchants.vendor', $vendor)
            ->where('merchants.is_active', '1')
            ->get(array('locations.id', 'locations.address', 'locations.city',
                'locations.state', 'locations.zip', 'locations.merchant_id',
                'locations.latitude', 'locations.longitude', 'locations.latm', 'locations.lngm'));
    }

}
<?php

class EloquentMerchantRepository extends BaseEloquentRepository implements MerchantRepository, ReviewableInterface, ShareableInterface, ViewableInterface, RepositoryInterface
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
        'page_title',
        'meta_description',
        'deleted_at',
        'keywords',
        'max_prints',
        'mobile_redemption',
        'rating',
        'is_displayed',
        'tnl_id',
        'old_id',
        'vendor',
        'service_radius',
        'new_disclaimer',
        'used_disclaimer',
        'page_version',
        'sub_heading',
        'offer_keywords',
        'entity_search_parse',
        'is_offer_notifications',
    );

    protected $model = 'Merchant';

    /**
     * Review this offer for a given user.
     *
     * @param PersonInterface $reviewer
     * 
     * @return Review
     */
    public function writeReview(PersonInterface $reviewer)
    {
        if($this->primary_key)
        {
            $review = Review::blank();
            $review->reviewable_id = $this->primary_key;
            $review->reviewable_type = 'Merchant';
            if($reviewer->getType() == 'User')
                $review->user_id = $reviewer->id;
            else
                $review->nonmember_id = $reviewer->id;
            $review->content = Input::get('content');
            $review->rating = Input::get('rating');
            $review->save();
            return $review;
        }
    }

    /**
     * Share this Merchant for a given user.
     *
     * @param UserRepository $sharer
     * @param string            $type The type of share, email or facebook.
     * @param array             $params An array of parameters.
     * 
     * @return Share
     */
    public function share(UserRepository $sharer, $type, $params = array())
    {
        if($this->primary_key)
        {

        }
    }

    /**
     * View this Merchant for a given person.
     *
     * @param PersonInterface $viewer
     */
    public function view(PersonInterface $viewer)
    {
        if($this->primary_key)
        {
            $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            $viewer_id = $viewer->id;
            $viewer_type = $viewer->getType();
            Queue::push(function($job) use ($viewer_id, $viewer_type, $user_agent)
            {
                $viewer = $viewer_type == 'User' ? User::find($viewer_id) : Nonmember::find($viewer_id);
                if(stristr($user_agent, 'bot') || stristr($user_agent, 'spider'))
                {
                    $job->delete();
                    return;
                }

                $view = UserView::blank();
                if($viewer->getType() == 'User')
                {
                    $view->user_id = $viewer->id;
                }
                else
                {
                    $view->nonmember_id = $viewer->id;
                }
                $view->merchant_id = $this->primary_key;
                $view->user_agent = $user_agent;
                $view->tracking_id = Cookie::get('tracking_id');
                $view->url = Cookie::get('tracking_url');
                $view->refer_id = Cookie::get('tracking_referid');
                $view->save();
                $job->delete();
            });
        }
    }

    /**
     * Get the locations for this merchant, ordered by distance.
     *
     * @param int   $page The page of search results, default 0.
     * @param int   $limit The number of search results to return, default all.
     * 
     * @return array Locations
     */
    public function getLocationsByDistance($page = 0, $limit = 0, $latitude, $longitude)
    {
        if($this->primary_key)
        {
            //$geoip = json_decode(GeoIp::getGeoIp('json'));
            $cartesian = SoeHelper::getCartesian($latitude, $longitude);
            $distance = DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1');
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
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
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = Location::blank();
                $loc = $loc->createFromModel($location);
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Get the states that this merchant has locations in.
     *
     * @param string    $state The state to search.
     * @param int       $page The page of search results, default 0.
     * @param int       $limit The number of search results to return, default all.
     * 
     * @return array States
     */
    public function getMerchantStates($page = 0, $limit = 0)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1')
                                    ->groupBy('state')
                                    ->orderBy('state');
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
            if($showDemo == false)
            {
                $query = $query->where('is_demo', '=', '0');
            }
            $stats = $this->getStats(clone $query, $page, $limit);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $states = $query->remember(600)->get(array('state', DB::raw('COUNT(*) as state_total')));
            $stats['stats']['returned'] = count($states);
            $results = array('objects' => array());
            foreach($states as $state)
            {
                $loc = Location::blank();
                $loc = $loc->createFromModel($state);
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Get the locations for this merchant in a given state.
     *
     * @param string    $state The state to search.
     * @param int       $page The page of search results, default 0.
     * @param int       $limit The number of search results to return, default all.
     * 
     * @return array Locations
     */
    public function getLocationsByState($state, $page = 0, $limit = 0)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1')
                                    ->where('state', '=', $state);
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
            if($showDemo == false)
            {
                $query = $query->where('is_demo', '=', '0');
            }
            $stats = $this->getStats(clone $query, $page, $limit);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $locations = $query->remember(600)->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = Location::blank();
                $results['objects'][] = $loc->createFromModel($location);
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Get the cities that this merchant has locations in a given state.
     *
     * @param string    $state The state to search.
     * @param int       $page The page of search results, default 0.
     * @param int       $limit The number of search results to return, default all.
     * 
     * @return array Cities
     */
    public function getMerchantCities($state, $page = 0, $limit = 0)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1')
                                    ->where('state', '=', $state)
                                    ->groupBy('city')
                                    ->orderBy('city');
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
            if($showDemo == false)
            {
                $query = $query->where('is_demo', '=', '0');
            }
            $stats = $this->getStats(clone $query, $page, $limit);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $cities = $query->remember(600)->get(array('city', DB::raw('COUNT(*) as city_total')));
            $stats['stats']['returned'] = count($cities);
            $results = array('objects' => array());
            foreach($cities as $city)
            {
                $loc = Location::blank();
                $results['objects'][] = $loc->createFromModel($city);
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Get the locations for this merchant in a given city, state.
     *
     * @param string    $city The city to search.
     * @param string    $state The state to search.
     * @param int       $page The page of search results, default 0.
     * @param int       $limit The number of search results to return, default all.
     * 
     * @return array Locations
     */
    public function getLocationsByCity($city, $state, $page = 0, $limit = 0)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1')
                                    ->where('state', '=', $state)
                                    ->where('city', '=', $city);
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
            if($showDemo == false)
            {
                $query = $query->where('is_demo', '=', '0');
            }
            $stats = $this->getStats(clone $query, $page, $limit);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $locations = $query->remember(600)->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = Location::blank();
                $loc = $loc->createFromModel($location);
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Find any merchants with the given slug.
     *
     * @param string    $slug The merchant slug.
     * 
     * @return array Merchants
     */
    public function getBySlug($slug)
    {
        $query = SOE\DB\Merchant::where('slug', '=', $slug)
                                ->where('is_active', '=', '1');
        $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
        if($showDemo == false)
        {
            $query = $query->where('is_demo', '=', '0');
        }

        $stats = $this->getStats(clone $query, 0, 0);
        $merchants = $query->get();
        $stats['stats']['returned'] = count($merchants);
        $return = array('objects' => array());
        foreach($merchants as $merchant)
        {
            $merch = Merchant::blank();
            $merch = $merch->createFromModel($merchant);
            $return['objects'][] = $merch;
        }

        return array_merge($return, $stats);
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
        $cartesian = SoeHelper::getCartesian($latitude, $longitude);
        $distance = DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                ->where('merchants.slug', '=', $slug)
                                ->where('merchants.is_active', '=', '1')
                                ->where('locations.is_active', '=', '1')
                                ->orderBy($distance);
        $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
        if($showDemo == false)
        {
            $query = $query->where('merchants.is_demo', '=', '0');
        }

        $location = $query->remember(600)->first(array('locations.*'));
        if(empty($location))
            return;
        $loc = Location::blank();
        $loc = $loc->createFromModel($location);

        return $loc;
    }

    /**
     * Find the nearest location for this merchant based on latitude and longitude.
     *
     * @param float    $latitude The latitude to search by.
     * @param float    $longitude The longitude to search by.
     * 
     * @return array Location
     */
    public function findNearestLocation($latitude, $longitude)
    {
        if($this->primary_key)
        {
            $cartesian = SoeHelper::getCartesian($latitude, $longitude);
            $distance = DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
            $query = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)
                                    ->where('is_national', '=', '0')
                                    ->where('is_active', '=', '1');
            $showDemo = Auth::check() ? User::createFromModel(Auth::User())->showDemo() : false;
            if($showDemo == false)
            {
                $query = $query->where('is_demo', '=', '0');
            }
            $locations = $query->orderBy($distance)->remember(600)->first();
            if(empty($locations))
                return;
            $loc = Location::blank();
            $loc = $loc->createFromModel($location);

            return $loc;

        }
    }

    /**
     * Get a list of merchants matching a name query.
     *
     * @param string    $name
     * @param int       $page
     * @param int       $limit
     *
     * @return array Merchants
     */
    public function getByName($name, $page = 0, $limit = 0)
    {
        $name = str_replace("'", '', $name);
        $query = SOE\DB\Merchant::where('name', 'LIKE', '%'.$name.'%')
                                ->where('is_active', '=', '1')
                                ->orderBy('name', 'asc');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $merchants = $query->get();
        $return = array('objects' => array());
        foreach($merchants as $merchant)
        {
            $merch = Merchant::blank();
            $merch = $merch->createFromModel($merchant);
            $return['objects'][] = $merch;
        }

        return array_merge($return, $stats);
    }

    /**
     * Get the Category for this merchant.
     *
     * @return Category
     */
    public function category()
    {
        if($this->primary_key)
        {
            return Category::find($this->category_id);
        }
    }

    /**
     * Get the subCategory for this merchant.
     *
     * @return subCategory
     */
    public function subcategory()
    {
        if($this->primary_key)
        {
            return Category::find($this->subcategory_id);
        }
    }

    /**
     * Get the logo asset for this merchant.
     *
     * @return Asset
     */
    public function logo()
    {
        if($this->primary_key)
        {
            $asset = SOE\DB\Asset::where('assetable_type', '=', 'Merchant')->where('assetable_id', '=', $this->primary_key)->where('name', '=', 'logo1')->first();
            if(empty($asset))
                return;
            $logo = Asset::blank();
            $logo = $logo->createFromModel($asset);
            return $logo;
        }
    }

    public function updateIndex()
    {
        if($this->primary_key && App::environment() == 'prod')
        {
            $locations = SOE\DB\Location::where('merchant_id', '=', $this->primary_key)->get(array('id'));
            foreach($locations as $location)
            {
                Artisan::call('search', array('--type' => 'merchant', '--id' => $location->id));
            }
        }
    }

    /***** API METHODS *****/

    /**
     * Get the locations for this merchant, ordered by distance based on merchant_id, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of locations.
     */
    public function apiGetLocationsByDistance()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $this->find($merchant_id);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        return $this->format($this->getLocationsByDistance($page, $limit, $latitude, $longitude));
    }

    /**
     * Get the states in which this merchant has locations, based on merchant_id, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of states.
     */
    public function apiGetMerchantStates()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $this->find($merchant_id);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getMerchantStates($page, $limit));
    }

    /**
     * Get the locations for this merchant in a given state, based on merchant_id, state, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of Locations.
     */
    public function apiGetLocationsByState()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $this->find($merchant_id);
        $state = Input::get('state');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getLocationsByState($state, $page, $limit));
    }

    /**
     * Get the cities in which this merchant has locations, based on merchant_id, state, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of cities.
     */
    public function apiGetMerchantCities()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $this->find($merchant_id);
        $state = Input::get('state');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getMerchantCities($state, $page, $limit));
    }

    /**
     * Get the cities in which this merchant has locations, based on merchant_id, city, state, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of locations.
     */
    public function apiGetLocationsByCity()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $this->find($merchant_id);
        $city = Input::get('city');
        $state = Input::get('state');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getLocationsByCity($city, $state, $page, $limit));
    }

    /**
     * Get a list of merchants matching a name query, based on name, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of merchants.
     */
    public function apiGetByName()
    {
        return $this->format($this->getByName(Input::get('name'), Input::get('page', 0), Input::get('limit', 0)));
    }
}

/**
 * Handle the Merchant updated event.
 *
 * @param SOE\DB\Merchant $merchant
 * @return void
 */
SOE\DB\Merchant::updated(function($merchant)
{
    $merchant_id = $merchant->id;
    Queue::push(function($job) use ($merchant_id)
    {
        $merchant = Merchant::find($merchant_id);
        $merchant->updateIndex();
        $job->delete();
    });
});

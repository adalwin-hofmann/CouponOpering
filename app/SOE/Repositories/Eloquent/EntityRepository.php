<?php namespace SOE\Repositories\Eloquent;

class EntityRepository extends BaseRepository implements \EntityRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'entitiable_id',
        'entitiable_type',
        'name',
        'slug',
        'location_id',
        'category_id',
        'subcategory_id',
        'latitude',
        'longitude',
        'path',
        'is_dailydeal',
        'rating',
        'special_price',
        'regular_price',
        'is_demo',
        'is_active',
        'starts_at',
        'expires_at',
        'rating_count',
        'savings',
        'url',
        'print_override',
        'secondary_type',
        'latm',
        'lngm',
        'merchant_id',
        'merchant_slug',
        'merchant_name',
        'popularity',
        'is_featured',
        'state',
        'expires_year',
        'expires_day',
        'starts_year',
        'starts_day',
        'category_slug',
        'subcategory_slug',
        'company_id',
        'company_name',
        'deleted_at',
        'stuffing_priority',
        'is_national',
        'is_certified',
        'project_tags',
        'service_radius',
        'short_name_line1',
        'short_name_line2',
        'category_visible',
        'hide_expiration',
        'is_reoccurring',
        'merchant_logo',
    );

    protected $model = 'Entity';
    protected $categoryRepository;
    protected $featureRepository;
    protected $projectTagRepository;
    protected $usedVehicleRepository;
    protected $vehicleEntityRepository;
    protected $vehicleStyleRepository;
    protected $zipcodeRepository;

    public function __construct(
        \CategoryRepositoryInterface $categoryRepository,
        \DistrictRepositoryInterface $districts,
        \FeatureRepositoryInterface $featureRepository,
        \ProjectTagRepositoryInterface $projectTagRepository,
        \UsedVehicleRepositoryInterface $usedVehicleRepository,
        \VehicleEntityRepositoryInterface $vehicleEntityRepository,
        \VehicleStyleRepositoryInterface $vehicleStyleRepository,
        \ZipcodeRepositoryInterface $zipcodeRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->districts = $districts;
        $this->featureRepository = $featureRepository;
        $this->projectTagRepository = $projectTagRepository;
        $this->usedVehicleRepository = $usedVehicleRepository;
        $this->vehicleEntityRepository = $vehicleEntityRepository;
        $this->vehicleStyleRepository = $vehicleStyleRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        parent::__construct();
    }

    public function getByEntitiable($id, $type)
    {
        return $this->query()
            ->where('entitiable_id', $id)
            ->where('entitiable_type', $type)
            ->get();
    }

    /**
     * Retrieve a set of SOHI recommendations based on various parmeters.
     *
     * @param boolean   $show_demo Whether or not to show demo entities.
     * @param object    $geoip_data A geoip object.
     * @param int       $limit How many recommendations to return, default all.
     * @param string    $ordering How to order the recommendations, default is 'rand'.
     * @return mixed
     */
    public function getSohiRecommendations($show_demo = false, $geoip_data = array(), $limit = 0, $ordering = 'rand')
    {
        /**
        *This is our method for recommending SOHI entities.
        *The process is similar to the getRecommendations function except it only takes
        *entities from the home improvement category.  These entities must belong to a 
        *certified franchise.
        */

        $geoip = $this->getLocationData($geoip_data);
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $entities = array();
        
        $aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        //Get featured entities to stuff
        $stuffed = array();
        //$stuffed = $this->getStuffing($aStates, $cartesian, $show_demo);
        $entities['stuffed'] = $stuffed;
        
        $entities['sohi'] = $this->getCategoryRecommended($cartesian, 'home_services', $aStates, 0, $stuffed, $show_demo, true);

        return $this->handleRecommendations($entities, $cartesian, $show_demo, $limit, $ordering);
    }

    /**
     * Retrieve a set of SOCT recomendations.
     *
     * @param boolean $show_demo Whether or not to show demo entities.
     * @param object $geoip_data A geoip object.
     * @param int $limit How many recommendations to return, default is all.
     * @param string $orderding How to order the recommendations, default is 'rand'.
     * @return mixed
     */
    public function getSoctRecommendations($show_demo = false, $geoip_data = array(), $limit = 0, $ordering = 'rand')
    {
        //$geoip = $this->getLocationData($geoip_data);
        $geoip = empty($geoip_data) ? json_decode(\GeoIp::getGeoIp('json')) : $geoip_data;
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $entities = array();
        //$aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        $new = $this->vehicleStyleRepository->search(null, null, null, null, null, 0, 15, $ordering);
        $used = $this->vehicleEntityRepository->search(null, null, null, null, null, null, 0, 15, $ordering, null, null, null, false);
        $deals = $this->query()->dealers()
                                ->active()
                                ->orderByDistance($cartesian)
                                ->demo($show_demo)
                                ->take(15)
                                ->limitByBox($cartesian, 20)
                                ->join('locations', 'entities.location_id', '=', 'locations.id')
                                ->remember(\Config::get('soe.cache', 60*24))
                                ->get(array('entities.*','locations.address','locations.address2','locations.city','locations.state','locations.zip','locations.phone', \DB::raw('locations.offer_count as total_entities')));

        $return = array('objects' => array());
        foreach($new['objects'] as $n)
        {
            $n->object_type = 'VehicleStyle';
            $return['objects'][] = $n;
        }
        foreach($used['objects'] as $u)
        {
            $u->object_type = 'UsedVehicle';
            $return['objects'][] = $u;
        }
        foreach($deals as $d)
        {
            $d->object_type = 'Entity';
            $return['objects'][] = $d;
        }

        shuffle($return['objects']);
        $total = count($return['objects']);
        if($limit != 0)
        {
            for($i=0; $i<$limit && $i<count($return['objects']); $i++)
            {
                $objects[$i] = $return['objects'][$i];
            }
            $return['objects'] = $objects;
        }
        $return['stats'] = array('limit' => ($limit ? $limit : 45), 'page' => 0, 'total' => $total, 'returned' => count($return['objects']));

        return $return;//$this->handleRecommendations($entities, $cartesian, $show_demo, $limit, $ordering);
    }

    /**
     * Retrieve a set of recommendations based on various parmeters.
     *
     * @param array     $ranks An array of category rankings.
     * @param array     $preferences An array of category preferences.
     * @param boolean   $show_demo Whether or not to show demo entities.
     * @param object    $geoip_data A geoip object.
     * @param int       $limit How many recommendations to return, default all.
     * @param string    $ordering How to order the recommendations, default is 'rand'.
     * @return mixed
     */
    public function getRecommendations($ranks, $preferences, $show_demo = false, $geoip_data = array(), $limit = 0, $ordering = 'rand', $stuffing = null, $entity_type = null, $exclusions = array())
    {
        /**
        *This is our method for recommending SOE entities.  It is based on user preferences in the members section.
        *There is a preference intact for every one of our major categories.
        *In the /members/my interests page, the default setting is to have all categories selected.
        *Users must manually uncheck categories.
        *In the remaining checked categories, each member is assigned a score for each category.
        *This score is based on the number of prints and views the user has in each category. (Behavioral recommendation)
        *The number of entities returned per category are according to the $aGroupings array. 
        *40 for the most-liked category, 25 for the second, and so on.
        *Then, suggestions are made, grabbing entities from the DB according to the groupings.
        *These suggestions are the most popular entities near the user.
        *These recommendations are then ordered as a function of popularity / distance from the user.
        *For nonmembers, there is a similar process, but preference is not a factor.
        */

        $geoip = $this->getLocationData($geoip_data);
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);

        arsort($ranks);
        $i=0;
        $entities = array();
        
        $aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        //Get featured entities to stuff
        $stuffed = $this->getStuffing($aStates, $cartesian, $show_demo, $geoip, $stuffing, $exclusions);
        $entities['stuffed'] = $stuffed;

        $entities['recommended'] = $this->getRecommended($ranks, $cartesian, $aStates, $stuffed, $show_demo, false, $limit, $entity_type, $preferences, $exclusions);

        /*foreach($ranks as $slug => $rank)
        {
            if(isset($preferences[$slug]) && $preferences[$slug] != 0)
            {
                $entities[$slug] = $this->getCategoryRecommended($cartesian, $slug, $aStates, $i, $stuffed, $show_demo, false, $limit, $entity_type);
                $i++;
            }
        }*/

        return $this->handleRecommendations($entities, $cartesian, $show_demo, $limit, $ordering, $ranks);
    }

    /**
     * Get a standardized geoip object.
     *
     * @param object    $geoip_data A geoip object.
     * @return object   The standardized geoip object.
     */
    protected function getLocationData($geoip_data)
    {
        $geoip = empty($geoip_data) ? json_decode(\GeoIp::getGeoIp('json')) : $geoip_data;

        $zipcode = $this->zipcodeRepository->getByCityState($geoip->city_name, $geoip->region_name, $geoip->latitude, $geoip->longitude);
        $geoip->latitude = $zipcode ? $zipcode->latitude : $geoip->latitude;
        $geoip->longitude = $zipcode ? $zipcode->longitude : $geoip->longitude;
        $geoip->region_name = $zipcode ? $zipcode->state : $geoip->region_name;
        $geoip->city_name = $zipcode ? $zipcode->city : $geoip->city_name;
        return $geoip;
    }

    /**
     * Handle the parsing of recommendations.
     *
     * @param array     $entities The array of recommended entities.
     * @param array     $cartesian An array of cartesian coordinates.
     * @param boolean   $show_demo Whether or not to show demo entities.
     * @param int       $limit The maximum number of recommendations to return, default all.
     * @param string    $ordering The method for ordering recommendations, default 'rand'.
     * @return mixed
     */
    protected function handleRecommendations($entities, $cartesian, $show_demo, $limit, $ordering, $ranks = null)
    {
        $aRecIDs = array(0);
        $aLocIDs = array(0);
        $aStuffIDs = array(0);
        foreach($entities as $grouping => $selected)
        {
            if($grouping == 'stuffed')
            {
                foreach($selected as $e)
                {
                    $aLocIDs[] = $e->location_id;
                }
            }
            else
            {
                foreach($selected as $e)
                {
                    $aLocIDs[] = $e->location_id;
                    $aRecIDs[] = $e->id;
                }
            }
        }

        $query = \SOE\DB\Entity::whereIn('entities.id', $aRecIDs)->join('locations', 'entities.location_id', '=', 'locations.id')->orderBy('is_yipit', 'asc');//->orderBy('entities.is_certified', 'desc');
        $get = array('entities.*','locations.address','locations.address2','locations.city','locations.state','locations.zip','locations.phone', \DB::raw('locations.offer_count as total_entities'), \DB::raw("IF(entities.company_id = 2, 1, 0) as is_yipit"));
        switch ($ordering) {
            case 'score':
                /*
                * score = (popularity * x) + (y / distance)
                * x = pop_score_multiplier in the features table
                * y = dist_score_multiplier in the features table
                */
                $pop_score_multiplier = $this->featureRepository->findByName('pop_score_multiplier');
                $psm = empty($pop_score_multiplier) ? 1 : $pop_score_multiplier->value;
                $dist_score_multiplier = $this->featureRepository->findByName('dist_score_multiplier');
                $dsm = empty($dist_score_multiplier) ? 1 : $dist_score_multiplier->value;
                $get[] = \DB::raw('((entities.popularity * '.$psm.') + ('.$dsm.'/ (sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))))) as score');
                $get[] = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
                $query->orderBy(\DB::raw('((entities.popularity * '.$psm.') + ('.$dsm.'/ (sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))))'), 'desc');
                break;

            default:
                $query = $query->orderBy(\DB::raw('RAND()'));
                break;
        }
        $rec_results = $query->remember(\Config::get('soe.cache', 60*60*24))->get($get);
        $astuffed = array();
        $aRecs = array();
        foreach($entities['stuffed'] as $stuff)
        {
            $astuffed[] = $stuff;
        }

        $aContIDs = array(0);
        foreach($rec_results as $entity)
        {
            if($entity->entitiable_type == 'Contest')
            {
                $aContIDs[] = $entity->entitiable_id;
            }
        }
        
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->remember(\Config::get('soe.cache', 60*24))->get(array('id', 'display_name'));
        $aContNames = array();
        foreach($contests as $contest)
        {
            $aContNames[$contest->id] = $contest->display_name;
        }

        foreach($rec_results as $rec)
        {
            $rec->display_name = ($rec->entitiable_type == 'Contest' && isset($aContNames[$rec->entitiable_id])) ? $aContNames[$rec->entitiable_id] : '';
            $aRecs[] = $rec;
        }
        $results = array('objects' => array());
        $results['objects'] = array_merge($astuffed, $aRecs);
        $results['stats'] = array('total' => count($results['objects']), 'page' => 0, 'take' => 0, 'returned' => count($results['objects']));

        if($limit != 0)
        {
            $aRecs;
            $results['objects'] = array();
            for($i = 0; $i < $limit && $i < count($aRecs); $i++)
            {
                $results['objects'][] = $aRecs[$i];
            }
            $results['stats']['returned'] = $limit > count($results) ? count($results) : $limit;
            $results['stats']['limit'] = $limit;
            foreach($results['objects'] as $ent)
            {
                $ent->is_clipped = 0;
                //$ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
            }
            return $results;
        }

        foreach($results['objects'] as $ent)
        {
            $ent->is_clipped = 0;
            //$ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
        }
        return $results;
    }

    /**
     * Retrieve entities to stuff into recommendations.
     *
     * @param array     $nearby_states An array of nearby states to search in.
     * @param array     $cartesian An array of cartesian latitude and longitude coordinates to search around.
     * @param boolean   $show_demo
     * @return array
     */
    protected function getStuffing($nearby_states, $cartesian, $show_demo, $geoip = null, $limit = null, $exclusions = array())
    {
        if($limit === 0)
            return array();
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $stuff_config = $this->featureRepository->findByName('recommendation_stuffing');
        $limit = !is_null($limit) ? $limit : (empty($stuff_config) ? 0 : $stuff_config->value);
        $radius = $this->featureRepository->findByName('banner_radius');
        $radius = empty($radius) ? 34000 : $radius->value;
        $query = \DB::table('entities')->where($distance, '<', $radius) //About 20 miles
                                        ->whereIn('state', $nearby_states)
                                        ->where('is_active', '=', '1')
                                        ->where('location_active', '=', '1')
                                        ->where('franchise_active', '=', '1')
                                        ->where('stuffing_priority', '!=', '0');
        if(!$show_demo)
        {
            $query = $query->where('is_demo', '=', '0')
                            ->where('franchise_demo', '=', '0');
        }
        if(count($exclusions))
        {
            $aString = $this->buildExcludeString($exclusions);
            $query->whereRaw('(entities.entitiable_id, entities.entitiable_type) NOT IN '.$aString);
        }
        $query = $this->queryNotExpired($query);
        // Removed order rand() and limit to make this query cachable
        $stuffing = $query->groupBy('location_id')
                            ->orderBy('stuffing_priority', 'asc')
                            //->orderBy(\DB::raw('RAND()'))
                            //->take($limit)
                            ->remember(\Config::get('soe.cache', 60*60*24))
                            ->get();
        // Group entities by stuffing priority, taking up to the limit
        $stuffed = array();
        for($i=0; $i<count($stuffing) && $i<$limit; $i++)
        {
            $stuffed[$stuffing[$i]->stuffing_priority][] = $stuffing[$i];
        }
        // Shuffle results within priority groupings
        foreach($stuffed as &$priority)
        {
            shuffle($priority);
        }

        // Insert hard-coded filler
        $detroit_quote_control = $this->featureRepository->findByName('detroit_quotes_only');
        $detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
        if($geoip && $detroit_quote_control)
        {
            $distance = \GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
            $detroit_quote_control = ($distance < $detroit_quote_control && $geoip->region_name == 'MI') ? 1 : 0;
        
            if($detroit_quote_control)
            {
                $filler = new \SOE\DB\Entity;
                $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/sohi_ad.jpg';
                $filler->url = '/homeimprovement/quote';
                $filler->entitiable_type = 'filler';
                $filler->expires_at = '2050-01-01 00:00:00';
                if(!isset($stuffed[0]))
                    $stuffed[0] = array();
                array_unshift($stuffed[0], $filler);
            }
            ksort($stuffed);
        }

        // Flatten into a single dimensional array
        /*function flatten(array $array) {
            $return = array();
            array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
            return $return;
        }*/
        return $this->flatten($stuffed);
    }

    protected function flatten(array $array)
    {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    /**
     * A more optimized way of getting recommendations.
     *
     */
    protected function getRecommended($ranks, $cartesian, $aStates, $stuffed, $show_demo, $get_certified = false,$limit, $entity_type, $preferences = null, $exclusions = array())
    {
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $distance_col = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
        $aGroupings = array(40, 25, 15, 10, 5, 3, 2);
        $aCategories = array(
            'food_dining' => 4,
            'home_services' => 231,
            'health_beauty' => 11,
            'auto_transportation' => 10,
            'travel_entertainment' => 5,
            'retail_fashion' => 8,
            'special_services' => 9
        );
        $aString = $this->buildExcludeString(array_merge($stuffed, $exclusions));
        $radius = $this->featureRepository->findByName('recommendation_dist');
        $radius = empty($radius) ? 34000 : $radius->value;
        $cert_only = $this->featureRepository->findByName('sohi_certified_only');
        $cert_only = empty($cert_only) ? 0 : $cert_only->value;
        $show_all = $this->featureRepository->findByName('sohi_show_all');
        $show_all = empty($show_all) ? 1 : $show_all->value;
        $aQueries = array();
        $i=0;
        //print_r($preferences);
        foreach($ranks as $slug => $rank)
        {
            if(isset($preferences[$slug]) && $preferences[$slug] != 0)
            {
                $query = \DB::table('entities')->select($distance_col)
                                                ->whereIn('entities.state', $aStates)
                                                ->where('entities.category_id', '=', $aCategories[$slug])
                                                ->whereRaw('(entities.entitiable_id, entities.entitiable_type) NOT IN '.$aString)
                                                //->where('entities.is_featured', '=', '0')
                                                ->where('entities.location_active', '=', '1')
                                                ->where('entities.franchise_active', '=', '1')
                                                ->where('entities.is_active', '=', '1')
                                                ->where(function($query)
                                                {
                                                    $query->where('entities.expires_year', '=', date('Y'))
                                                        ->where('entities.expires_day', '>=', (date('z')+8))
                                                        ->orWhere('entities.expires_year', '>=', (date('Y')+1));
                                                })
                                                ->orderBy('entities.is_certified', 'desc');
                $query = $this->queryNotExpired($query);
                if($show_demo == false)
                {
                    $query = $query->where('entities.is_demo', '=', '0')
                                    ->where('entities.franchise_demo', '=', '0');
                }
                if($get_certified == true)
                {
                    $cert_only = $this->featureRepository->findByName('sohi_certified_only');
                    $cert_only = empty($cert_only) ? 0 : $cert_only->value;
                    $show_all = $this->featureRepository->findByName('sohi_show_all');
                    $show_all = empty($show_all) ? 1 : $show_all->value;
                    if($cert_only)
                    {
                        $query = $query->where('entities.is_certified', '=', '1');
                    }
                    else if(!$show_all)
                    {
                        $query = $query->where(function($query)
                                        {
                                            $query->where('entities.is_certified', '=', 1);
                                            $query->orWhere(function($query)
                                            {
                                                $query->where('entities.is_sohi_trial', '=', '1');
                                                $query->where('entities.sohi_trial_ends_at', '>', \DB::raw('NOW()'));
                                            });
                                        });
                    }
                    else
                    {
                        $query = $query;
                    }
                }

                switch($entity_type)
                {
                    case 'coupon':
                        $query = $query->where('entities.entitiable_type', 'Offer')
                                        ->where('entities.is_dailydeal', '0');
                        break;
                    case 'dailydeal':
                        $query = $query->where('entities.entitiable_type', 'Offer')
                                        ->where('entities.is_dailydeal', '1');
                        break;
                    case 'contest':
                        $query = $query->where('entities.entitiable_type', 'Contest');
                        break;
                }

                /*
                * score = (popularity * x) + (y / distance)
                * x = grab_pop_score_multiplier in the features table
                * y = grab_dist_score_multiplier in the features table
                */
                $pop_score_multiplier = $this->featureRepository->findByName('grab_pop_score_multiplier');
                $psm = empty($pop_score_multiplier) ? 1 : $pop_score_multiplier->value;
                $dist_score_multiplier = $this->featureRepository->findByName('grab_dist_score_multiplier');
                $dsm = empty($dist_score_multiplier) ? 1 : $dist_score_multiplier->value;
                $query->orderBy('is_yipit', 'asc');
                $query = $query->orderBy(\DB::raw('((entities.popularity * '.$psm.') + ('.$dsm.'/ distance))'), 'desc');
                $query->groupBy('entities.merchant_id')
                            ->take($aGroupings[$i++])
                            ->remember(\Config::get('soe.cache', 60*60*24))
                            ->having('distance', '<', $radius)
                            ->addSelect(array('entities.id', 'entities.entitiable_id', 'entities.entitiable_type', 'entities.location_id', \DB::raw("IF(entities.company_id = 2, 1, 0) as is_yipit")));
                $aQueries[] = $query;
            }
        }
        if(!count($aQueries))
            return array();
        $full_query = $aQueries[0];
        for($i=1; $i<count($aQueries); $i++)
        {
            $full_query = $full_query->union($aQueries[$i]);
        }
        $results = $full_query->get(array('entities.id', 'entities.entitiable_id', 'entities.entitiable_type', 'entities.location_id'));
        return $results;
    }

    protected function getCategoryRecommended($cartesian, $slug, $nearby_states, $rank, $excluded, $show_demo, $get_certified = false, $limit = null, $entity_type = null)
    {
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $aGroupings = array(40, 25, 15, 10, 5, 3, 2);
        $aCategories = array(
            'food_dining' => 4,
            'home_services' => 231,
            'health_beauty' => 11,
            'auto_transportation' => 10,
            'travel_entertainment' => 5,
            'retail_fashion' => 8,
            'special_services' => 9
        );
        $aString = $this->buildExcludeString($excluded);
        $radius = $this->featureRepository->findByName('recommendation_dist');
        $radius = empty($radius) ? 34000 : $radius->value;
        $query = \DB::table('entities')->where($distance, '<', $radius) //About 20 miles
                                        ->whereIn('entities.state', $nearby_states)
                                        ->where('entities.category_id', '=', $aCategories[$slug])
                                        ->whereRaw('(entities.entitiable_id, entities.entitiable_type) NOT IN '.$aString)
                                        //->where('entities.is_featured', '=', '0')
                                        ->where('entities.location_active', '=', '1')
                                        ->where('entities.franchise_active', '=', '1')
                                        ->where('entities.is_active', '=', '1')
                                        ->orderBy('entities.is_certified', 'desc');
        $query = $this->queryNotExpired($query);
        if($show_demo == false)
        {
            $query = $query->where('entities.is_demo', '=', '0')
                            ->where('entities.franchise_demo', '=', '0');
        }
        if($get_certified == true)
        {
            $cert_only = $this->featureRepository->findByName('sohi_certified_only');
            $cert_only = empty($cert_only) ? 0 : $cert_only->value;
            $show_all = $this->featureRepository->findByName('sohi_show_all');
            $show_all = empty($show_all) ? 1 : $show_all->value;
            if($cert_only)
            {
                $query = $query->where('entities.is_certified', '=', '1');
            }
            else if(!$show_all)
            {
                $query = $query->where(function($query)
                                {
                                    $query->where('entities.is_certified', '=', 1);
                                    $query->orWhere(function($query)
                                    {
                                        $query->where('entities.is_sohi_trial', '=', '1');
                                        $query->where('entities.sohi_trial_ends_at', '>', \DB::raw('NOW()'));
                                    });
                                });
            }
            else
            {
                $query = $query;
            }
        }

        switch($entity_type)
        {
            case 'coupon':
                $query = $query->where('entities.entitiable_type', 'Offer')
                                ->where('entities.is_dailydeal', '0');
                break;
            case 'dailydeal':
                $query = $query->where('entities.entitiable_type', 'Offer')
                                ->where('entities.is_dailydeal', '1');
                break;
            case 'contest':
                $query = $query->where('entities.entitiable_type', 'Contest');
                break;
        }

        if($limit)
        {
            $query = $query->take($limit);
        }

        /*
        * score = (popularity * x) + (y / distance)
        * x = grab_pop_score_multiplier in the features table
        * y = grab_dist_score_multiplier in the features table
        */
        $pop_score_multiplier = $this->featureRepository->findByName('grab_pop_score_multiplier');
        $psm = empty($pop_score_multiplier) ? 1 : $pop_score_multiplier->value;
        $dist_score_multiplier = $this->featureRepository->findByName('grab_dist_score_multiplier');
        $dsm = empty($dist_score_multiplier) ? 1 : $dist_score_multiplier->value;
        $query = $query->orderBy(\DB::raw('((entities.popularity * '.$psm.') + ('.$dsm.'/ (sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))))'), 'desc');
        return $query->groupBy('entities.merchant_id')
                    ->take($aGroupings[$rank])
                    //->remember(\Config::get('soe.cache', 60*60*24))
                    ->get(array('entities.id', 'entities.entitiable_id', 'entities.entitiable_type', 'entities.location_id'));
    }

    protected function buildExcludeString($excluded)
    {
        $aString = "((0,'Offer'),";
        foreach($excluded as $exclude)
        {
            if($exclude->entitiable_type == 'filler')
                continue;
            $aString .= "(".$exclude->entitiable_id.",'".$exclude->entitiable_type."'),";
        }
        $aString = rtrim($aString, ',');
        $aString .= ')';
        return $aString;
    }

    public function getSponsors($district_slug, $sort = 'nearest', $page = 0, $limit = 12)
    {
        $district = $this->districts->query()->where('slug', $district_slug)->first();
        $query = \SOE\DB\Entity::join('locations', 'entities.location_id', '=', 'locations.id')
            ->join('franchises', 'locations.franchise_id', '=', 'franchises.id')
            ->join('franchise_districts', 'franchises.id', '=', 'franchise_districts.franchise_id')
            ->where('franchises.sponsor_level', 'bronze')
            ->where('franchise_districts.district_id', $district ? $district->id : 0)
            ->where('entities.category_visible', '1')
            ->where('entities.entitiable_type', '=', 'Offer')
            ->where('entities.is_dailydeal', '=', '0')
            ->where('entities.is_demo', '=', '0')
            ->where('entities.franchise_demo', '=', '0')
            ->where('entities.is_active', '=', '1')
            ->where('entities.location_active', '=', '1')
            ->where('entities.franchise_active', '=', '1');
        $query = $this->queryNotExpired($query);
        $entities = $query->groupBy('entities.entitiable_id')
            ->remember(\Config::get('soe.cache', 60*60*24))
            ->get(array('entities.id'));

        return $this->handleSponsors($entities, $sort, $page, $limit);
    }

    protected function handleSponsors($sub_entities, $sort, $page, $limit)
    {
        $aSubIDs = array(0);
        foreach($sub_entities as $se)
        {
            $aSubIDs[] = $se->id;
        }
        $query = \SOE\DB\Entity::whereIn('entities.id', $aSubIDs);
        $query->groupBy('entities.location_id')->orderBy('entities.is_certified', 'desc');
        $stats = $this->getStats(clone $query, $limit, $page, true);
        $get = array();
        $query = $query->join('locations', 'entities.location_id', '=', 'locations.id')
                        ->join('offers', 'entities.entitiable_id', '=', 'offers.id')
                        ->orderBy('is_yipit', 'asc');
        $get[] = \DB::raw("IF(offers.yipitdeal_id = 0, 0, 1) as is_yipit");
        $get[] = \DB::raw('locations.offer_count as total_entities');
        $get[] = 'locations.address';
        $get[] = 'locations.address2';
        $get[] = 'locations.city';
        $get[] = 'locations.state';
        $get[] = 'locations.zip';
        $get[] = 'locations.phone';
        $get[] = 'locations.is_address_hidden';
        $get[] = 'locations.custom_address_text';
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);

        switch ($sort) {
            case 'nearest':
                $query = $query->orderBy('distance');
                break;
            case 'furthest':
                $query = $query->orderBy('distance', 'desc');
                break;
            case 'popular':
                # code...
                break;
            case 'az':
                $query = $query->orderBy('entities.name');
                break;
            case 'za':
                $query = $query->orderBy('entities.name', 'desc');
                break;
            default:
                $query = $query->orderBy('distance');
                break;
        }

        $get[] = 'entities.*';
        $get[] = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');;
        $entities = $query->take($limit)->skip($limit*$page)
                ->remember(\Config::get('soe.cache', 60*60*24))
                ->get($get);
        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        
        foreach($entities as $entity)
        {
            $entity->is_clipped = 0;
            $entity->display_name = '';
            $results['objects'][] = $entity;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    public function getByCategory($city, $state, $lat, $lng, $show_demo = false, $type = null, $filterEntered = false, $category_id = null, $sort = 'nearest', $page = 0, $limit = 12, $radius = 0)
    {
        $geoip = new \StdClass;
        $geoip->city_name = $city;
        $geoip->region_name = $state;
        $geoip->latitude = $lat;
        $geoip->longitude = $lng;
        $geoip = $this->getLocationData($geoip);
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        $category = $this->categoryRepository->find($category_id);
        
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $category_distance = $this->featureRepository->findByName('category_dist');
        $category_distance = empty($category_distance) ? 34000 : $category_distance->value;

        switch(strtolower($type)) {
            case 'coupon':
                $wheres = "and `entities`.`state` in (".implode(',', array_map(function($st){return "'".$st."'";}, $aStates)).") ";
                $wheres .= "and (sqrt(pow(entities.latm - ".$cartesian['latm'].", 2) + pow(entities.lngm - ".$cartesian['lngm'].", 2))) < service_radius ";
                $wheres .= "and `category_visible` = 1 ";
                $wheres .= "and `entitiable_type` = 'Offer' and `is_dailydeal` = 0 ";
                break;
            case 'dailydeal':
                $wheres = "and `entities`.`state` in (".implode(',', array_map(function($st){return "'".$st."'";}, $aStates)).") ";
                $wheres .= "and (sqrt(pow(entities.latm - ".$cartesian['latm'].", 2) + pow(entities.lngm - ".$cartesian['lngm'].", 2))) < service_radius ";
                $wheres .= "and `entitiable_type` = 'Offer' and `is_dailydeal` = 1 ";
                break;
            case 'contest':
                $wheres = "and `entitiable_type` = 'Contest' ";
                $wheres .= "and ((`entities`.`state` in (".implode(',', array_map(function($st){return "'".$st."'";}, $aStates)).") and (sqrt(pow(entities.latm - ".$cartesian['latm'].", 2) + pow(entities.lngm - ".$cartesian['lngm'].", 2))) < service_radius) or `entities`.`is_national` = 1) ";
                //$wheres .= "and (`entities`.`starts_year` = ".date('Y')." and `entities`.`starts_day` <= ".(date('z')+1)." or `entities`.`starts_year` < ".(date('Y')).") ";
                //$wheres .= "and (`entities`.`expires_year` = ".date('Y')." and `entities`.`expires_day` >= ".(date('z')+1)." or `entities`.`expires_year` >= ".(date('Y')+1).") ";
                break;
            default:
                break;
        }
        if(empty($category))
        {
            $wheres = $wheres;
        }
        else if($category->parent_id == 0)
        {
            $wheres .= "and (`entities`.`category_id` = ".$category_id." or `entities`.`category_id` = 0) ";
        }
        else
        {
            $wheres .= "and `entities`.`subcategory_id` = ".$category_id." ";
        }
        if(!$show_demo)
        {
            $wheres .= "and `entities`.`is_demo` = 0 and `entities`.`franchise_demo` = 0 ";
        }
        $wheres .= "and (`entities`.`starts_year` = ".date('Y')." and `entities`.`starts_day` <= ".(date('z')+1)." or `entities`.`starts_year` < ".(date('Y')).") ";
        $wheres .= "and (`entities`.`expires_year` = ".date('Y')." and `entities`.`expires_day` >= ".(date('z')+1)." or `entities`.`expires_year` >= ".(date('Y')+1).") ";
        $entities = $this->query()
            ->join(\DB::raw("(select `entities`.`id`, min((sqrt(pow(entities.latm - ".$cartesian['latm'].", 2) + pow(entities.lngm - ".$cartesian['lngm'].", 2)))) as min_distance, `entities`.`entitiable_id`, `entities`.`entitiable_type` from `entities` 
                where `entities`.`deleted_at` is null ".$wheres."
                    and `is_active` = 1 
                    and `location_active` = 1 
                    and `franchise_active` = 1 
                group by entitiable_id, entitiable_type) mins"), function($join) use ($distance)
            {
                $join->on('entities.entitiable_id', '=', 'mins.entitiable_id')
                    ->on($distance, '=', 'mins.min_distance');
            })
            ->groupBy('entities.entitiable_id')
            ->groupBy('entities.entitiable_type')
            ->remember(\Config::get('soe.cache', 60*60*24))
            ->get(array('entities.id'));

        return $this->handleCategory($cartesian, $entities, $show_demo, $type, $sort, $page, $limit);
    }

    public function getBySohiCategory($city, $state, $lat, $lng, $show_demo = false, $category_id = null, $sort = 'nearest', $page = 0, $limit = 12, $radius = 0)
    {
        $geoip = new \StdClass;
        $geoip->city_name = $city;
        $geoip->region_name = $state;
        $geoip->latitude = $lat;
        $geoip->longitude = $lng;
        $geoip = $this->getLocationData($geoip);
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        $sohi_category = $this->projectTagRepository->find($category_id);
        $category = $this->categoryRepository->findBySlug('home-improvement');
        
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $category_distance = $this->featureRepository->findByName('category_dist');
        $category_distance = empty($category_distance) ? 34000 : $category_distance->value;
        $query = \SOE\DB\Entity::whereIn('entities.state', $aStates)
                                ->where($distance, '<', \DB::raw('service_radius')) //About 10 miles
                                ->where('entities.entitiable_type', '=', 'Offer')->where('entities.is_dailydeal', '=', '0')
                                ->where('entities.category_id', '=', $category->id);
                                
        $cert_only = $this->featureRepository->findByName('sohi_certified_only');
        $cert_only = empty($cert_only) ? 0 : $cert_only->value;
        $show_all = $this->featureRepository->findByName('sohi_show_all');
        $show_all = empty($show_all) ? 1 : $show_all->value;
        if($cert_only)
        {
            $query = $query->where('entities.is_certified', '=', 1);
        }
        else if(!$show_all)
        {
            $query = $query->where(function($query)
                            {
                                $query->where('entities.is_certified', '=', 1);
                                $query->orWhere(function($query)
                                {
                                    $query->where('entities.is_sohi_trial', '=', '1');
                                    $query->where('entities.sohi_trial_ends_at', '>', \DB::raw('NOW()'));
                                });
                            });
        }
        
        if(empty($sohi_category))
        {
            $query = $query;
        }
        else
        {
            $child_tags = \DB::table('project_tag_relation')->where('parent_id', '=', $category_id)->get();
            if(empty($child_tags))
            {
                $query = $query->where(function($query) use ($category_id)
                                    {
                                        $query->where(\DB::raw("find_in_set('".$category_id."',entities.project_tags)"), '!=', 0);
                                    });
            }
            else
            {
                $query = $query->where(function($query) use ($child_tags)
                                    {
                                        $i=0;
                                        foreach($child_tags as $tag)
                                        {
                                            if($i == 0)
                                                $query->where(\DB::raw("find_in_set('".$tag->child_id."',entities.project_tags)"), '!=', 0);
                                            else
                                                $query->orWhere(\DB::raw("find_in_set('".$tag->child_id."',entities.project_tags)"), '!=', 0);
                                            $i++;
                                        }
                                    });
            }
        }
        if(!$show_demo)
        {
            $query = $query->where('entities.is_demo', '=', '0')
                            ->where('entities.franchise_demo', '=', '0');
        }
        $sub_entities = $query->where('entities.is_active', '=', '1')
                            ->where('entities.location_active', '=', '1')
                            ->where('entities.franchise_active', '=', '1');
        $sub_entities = $this->queryNotExpired($sub_entities);
        $sub_entities = $sub_entities->groupBy('entitiable_id')
                                    ->remember(\Config::get('soe.cache', 60*60*24))
                                    ->get(array('entities.id'));

        return $this->handleCategory($cartesian, $sub_entities, $show_demo, 'coupon', $sort, $page, $limit);
    }

    protected function handleCategory($cartesian, $sub_entities, $show_demo, $type, $sort, $page, $limit)
    {
        $aEids = array(0);
        foreach($sub_entities as $sub)
            $aEids[] = $sub->id;
        $query = \SOE\DB\Entity::whereIn('entities.id', $aEids);
        if($type != 'contest')
            $query->groupBy('entities.location_id')->orderBy('entities.is_certified', 'desc');
        $stats = $this->getStats(clone $query, $limit, $page, true);
        $get = array();
        switch(strtolower($type)) {
            case 'coupon':
            case 'dailydeal':
                $query = $query->join('locations', 'entities.location_id', '=', 'locations.id')
                                ->join('offers', 'entities.entitiable_id', '=', 'offers.id')
                                ->orderBy('is_yipit', 'asc');
                $get[] = \DB::raw("IF(offers.yipitdeal_id = 0, 0, 1) as is_yipit");
                $get[] = \DB::raw('locations.offer_count as total_entities');
                $get[] = 'locations.address';
                $get[] = 'locations.address2';
                $get[] = 'locations.city';
                $get[] = 'locations.state';
                $get[] = 'locations.zip';
                $get[] = 'locations.phone';
                $get[] = 'locations.is_address_hidden';
                $get[] = 'locations.custom_address_text';
                break;
            case 'contest':
                $get[] = \DB::raw('0 as total_entities');
                break;
            default:
                $query->join('locations', 'entities.location_id', '=', 'locations.id');
                break;
        }
        switch ($sort) {
            case 'nearest':
                $query = $query->orderBy('distance');
                break;
            case 'furthest':
                $query = $query->orderBy('distance', 'desc');
                break;
            case 'popular':
                # code...
                break;
            case 'az':
                $query = $query->orderBy('entities.name');
                break;
            case 'za':
                $query = $query->orderBy('entities.name', 'desc');
                break;
            default:
                $query = $query->orderBy('distance');
                break;
        }
        $get[] = 'entities.*';
        $get[] = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');;
        $entities = $query->take($limit)->skip($limit*$page)
                ->remember(\Config::get('soe.cache', 60*60*24))
                ->get($get);
        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        $aIDs = array(0);
        $aEntIDs = array(0);
        $aContIDs = array(0);
        foreach($entities as $entity)
        {
            $aIDs[] = $entity->location_id;
            if($entity->entitiable_type == 'Contest')
            {
                $aContIDs[] = $entity->entitiable_id;
            }
        }
        
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->remember(\Config::get('soe.cache', 60*24))->get(array('id', 'display_name'));
        $aContNames = array();
        foreach($contests as $contest)
        {
            $aContNames[$contest->id] = $contest->display_name;
        }
        foreach($entities as $entity)
        {
            $entity->is_clipped = 0;
            $entity->display_name = ($entity->entitiable_type == 'Contest' && isset($aContNames[$entity->entitiable_id])) ? $aContNames[$entity->entitiable_id] : '';
            $results['objects'][] = $entity;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Get a collection of featured entities.
     *
     * @param string            $type The type of entities to retrieve.
     * @param float             $latitude The latitude to look around.
     * @param float             $longitude The longitude to look around.
     * @param boolean           $show_demo Whether or not to show demo entities.
     * @param int               $radius Search radius in meters.
     * @param string            $category_slug The category of entites to retrieve.
     * @param string            $subcategory_slug The subcategory of entities to retrieve.
     * @return mixed
     */
    public function getFeatured($type, $latitude, $longitude, $show_demo, $radius = 0, $category_slug = 'all', $subcategory_slug = '')
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $ads_feature = $this->featureRepository->findByName('show_ads');
        $ads_feature = empty($ads_feature) ? 0 : $ads_feature->value;
        $aStates = $this->zipcodeRepository->getSurroundingStates($latitude, $longitude);
        $featured_radius = $this->featureRepository->findByName('banner_radius');
        $featured_radius = empty($featured_radius) ? 34000 : $featured_radius->value;
        $query = \SOE\DB\Entity::whereIn('state', $aStates)
                                ->where('is_featured', '=', '1')
                                ->where($distance, '<', ($radius == 0 ? $featured_radius : $radius))
                                ->where('is_active', '=', '1');
        $query = $this->queryNotExpired($query);
        switch(strtolower($type))
        {
            case 'offer':
                $query = $query->where(function($query) use ($ads_feature)
                                {
                                    $query->where('entitiable_type', '=', 'Offer');
                                    if($ads_feature)
                                        $query->orWhere('entitiable_type', '=', 'Ad');
                                })
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->where('is_dailydeal', '=', '0');
                break;
            case 'dailydeal':
                $query = $query->where(function($query) use ($ads_feature)
                                {
                                    $query->where('entitiable_type', '=', 'Offer');
                                    if($ads_feature)
                                        $query->orWhere('entitiable_type', '=', 'Ad');
                                })
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->where('is_dailydeal', '=', '1');
                break;
            case 'contest':
                $query = $query->where(function($query) use ($ads_feature)
                                {
                                    $query->where('entitiable_type', '=', 'Contest');
                                    if($ads_feature)
                                        $query->orWhere('entitiable_type', '=', 'Ad');
                                });

                break;
        }
        if($show_demo == false)
        {
            $query = $query->where('is_demo', '=', '0')
                            ->where('franchise_demo', '=', '0');
        }
        $query = $query->groupBy('entitiable_id')->groupBy('entitiable_type');

        $repeat_query = clone $query;
        if(strtolower($type) != 'contest')
        {
            if($category_slug != 'all')
                $query = $this->queryCategoryFilters($query, $category_slug);
            if($subcategory_slug != '')
                $query = $this->queryCategoryFilters($query, '', $subcategory_slug);
        }
        $entities = $query->orderBy(\DB::raw('RAND()'))
                        ->take(3)
                        ->remember(\Config::get('soe.cache', 60*60*24))
                        ->get();
        /*** Do not search for featured entities by category when viewing a subcategory ***/
        /*if(!count($entities) && strtolower($type) != 'contest')
        {
            if($category_slug != 'all')
            {
                $repeat_query = $this->queryCategoryFilters($repeat_query, $category_slug);
            }
            $entities = $repeat_query->orderBy(\DB::raw('RAND()'))
                        ->take(3)
                        ->remember(\Config::get('soe.cache', 60*60*24))
                        ->get();
        }*/
        $aContIDs = array(0);
        foreach($entities as $entity)
        {
            $aIDs[] = $entity->location_id;
            if($entity->entitiable_type == 'Contest')
            {
                $aContIDs[] = $entity->entitiable_id;
            }
        }
        
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->remember(\Config::get('soe.cache', 60*24))->get(array('id', 'display_name'));
        $aContNames = array();
        foreach($contests as $contest)
        {
            $aContNames[$contest->id] = $contest->display_name;
        }
        $aMerchIDs = array(0);
        foreach($entities as $entity)
        {
            $aMerchIDs[] = $entity->merchant_id;
        }

        $logos = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                            ->where('assetable_type', '=', 'Merchant')
                            ->where('name', '=', 'logo1')
                            ->remember(\Config::get('soe.cache', 60*60*24))
                            ->get();
        $abouts = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                            ->where('assetable_type', '=', 'Merchant')
                            ->where('name', 'LIKE', 'smallImage%')
                            ->groupBy('assetable_id')
                            ->remember(\Config::get('soe.cache', 60*60*24))
                            ->get();
        $aAssets = array();
        foreach($logos as $logo)
        {
            if(isset($aAssets[$logo->assetable_id]))
            {
                $aAssets[$logo->assetable_id]['logo'] = $logo->path;
            }
            else
            {
                $aAssets[$logo->assetable_id] = array('logo' => $logo->path, 'about' => '');
            }
        }

        foreach($abouts as $about)
        {
            if(isset($aAssets[$about->assetable_id]))
            {
                $aAssets[$about->assetable_id]['about'] = $about->path;
            }
            else
            {
                $aAssets[$about->assetable_id] = array('about' => $about->path, 'logo' => '');
            }
        }

        $return = array('objects' => array(), 'stats' => array('total' => count($entities), 'take' => count($entities), 'page' => 0, 'returned' => count($entities)));
        foreach($entities as $entity)
        {
            $entity->is_clipped = 0;
            $entity->logo = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['logo'] : '';
            $entity->about_img = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['about'] : '';
            $entity->display_name = ($entity->entitiable_type == 'Contest' && isset($aContNames[$entity->entitiable_id])) ? $aContNames[$entity->entitiable_id] : '';
            $return['objects'][] = $entity;
        }
        $sohi = $this->featureRepository->findByName('home_improvement');
        $sohi = empty($sohi) ? 0 : $sohi->value;

        $aFiller = $this->getFeaturedFiller($type, $aStates, $distance, $radius, $featured_radius, $show_demo, $category_slug, $subcategory_slug, $sohi, $return['objects']);
        foreach($aFiller as $filler)
        {
            if(count($return['objects']) < 3)
            {
                $return['objects'][] = $filler;
            }
        }

        shuffle($return['objects']);

        for($i=0; count($return['objects']) > 3; $i++)
        {
            array_pop($return['objects']);
        }
        $return['stats']['returned'] = count($return['objects']);
        return $return;
    }

    private function getFeaturedFiller(
        $type, $aStates, $distance,
        $radius, $featured_radius, $show_demo,
        $category_slug, $subcategory_slug, $sohi,
        $existing
    )
    {
        if(count($existing) >= 3)
            return array();


        $aFiller = array();
        switch ($type) {
            case 'contest':
                $aExisting = array(0);
                foreach($existing as $exist)
                    $aExisting[] = $exist->entitiable_id;
                $query = \SOE\DB\Entity::whereIn('state', $aStates)
                                ->where('is_featured', '=', '0')
                                ->where($distance, '<', ($radius == 0 ? $featured_radius : $radius))
                                ->where('is_active', '=', '1');
                $query = $this->queryNotExpired($query);
                $query->where('entitiable_type', '=', 'Contest')
                    ->whereNotIn('entitiable_id', $aExisting);
                if($show_demo == false)
                {
                    $query = $query->where('is_demo', '=', '0')
                                    ->where('franchise_demo', '=', '0');
                }
                $query = $query->groupBy('entitiable_id')->groupBy('entitiable_type');
                $entities = $query->orderBy(\DB::raw('RAND()'))
                                ->take(3)
                                ->remember(\Config::get('soe.cache', 60*60*24))
                                ->get();

                $aMerchIDs = array(0);
                foreach($entities as $entity)
                {
                    $aMerchIDs[] = $entity->merchant_id;
                }

                $logos = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                                    ->where('assetable_type', '=', 'Merchant')
                                    ->where('name', '=', 'logo1')
                                    ->remember(\Config::get('soe.cache', 60*60*24))
                                    ->get();
                $abouts = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                                    ->where('assetable_type', '=', 'Merchant')
                                    ->where('name', 'LIKE', 'smallImage%')
                                    ->groupBy('assetable_id')
                                    ->remember(\Config::get('soe.cache', 60*60*24))
                                    ->get();
                $aAssets = array();
                foreach($logos as $logo)
                {
                    if(isset($aAssets[$logo->assetable_id]))
                    {
                        $aAssets[$logo->assetable_id]['logo'] = $logo->path;
                    }
                    else
                    {
                        $aAssets[$logo->assetable_id] = array('logo' => $logo->path, 'about' => '');
                    }
                }

                foreach($abouts as $about)
                {
                    if(isset($aAssets[$about->assetable_id]))
                    {
                        $aAssets[$about->assetable_id]['about'] = $about->path;
                    }
                    else
                    {
                        $aAssets[$about->assetable_id] = array('about' => $about->path, 'logo' => '');
                    }
                }

                $return = array('objects' => array(), 'stats' => array('total' => count($entities), 'take' => count($entities), 'page' => 0, 'returned' => count($entities)));
                foreach($entities as $entity)
                {
                    $entity->is_clipped = 0;
                    $entity->logo = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['logo'] : '';
                    $entity->about_img = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['about'] : '';
                    $return['objects'][] = $entity;
                }

                $aFiller = $entities;
                break;
            case 'dailydeal':
                $query = \SOE\DB\Entity::whereIn('state', $aStates)
                                ->where('is_featured', '=', '0')
                                ->where($distance, '<', ($radius == 0 ? $featured_radius : $radius))
                                ->where('is_active', '=', '1');
                $query = $this->queryNotExpired($query);
                $query = $query->where('entitiable_type', '=', 'Offer')
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->where('is_dailydeal', '=', '1');

                if($show_demo == false)
                {
                    $query = $query->where('is_demo', '=', '0')
                                    ->where('franchise_demo', '=', '0');
                }
                $query = $query->groupBy('entitiable_id')->groupBy('entitiable_type');
                if($category_slug != 'all')
                    $query = $this->queryCategoryFilters($query, $category_slug);
                if($subcategory_slug != '')
                    $query = $this->queryCategoryFilters($query, '', $subcategory_slug);
                $entities = $query->orderBy(\DB::raw('RAND()'))
                                ->take(3)
                                ->remember(\Config::get('soe.cache', 60*60*24))
                                ->get();

                $aMerchIDs = array(0);
                foreach($entities as $entity)
                {
                    $aMerchIDs[] = $entity->merchant_id;
                }

                $logos = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                                    ->where('assetable_type', '=', 'Merchant')
                                    ->where('name', '=', 'logo1')
                                    ->remember(\Config::get('soe.cache', 60*60*24))
                                    ->get();
                $abouts = \SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                                    ->where('assetable_type', '=', 'Merchant')
                                    ->where('name', 'LIKE', 'smallImage%')
                                    ->groupBy('assetable_id')
                                    ->remember(\Config::get('soe.cache', 60*60*24))
                                    ->get();
                $aAssets = array();
                foreach($logos as $logo)
                {
                    if(isset($aAssets[$logo->assetable_id]))
                    {
                        $aAssets[$logo->assetable_id]['logo'] = $logo->path;
                    }
                    else
                    {
                        $aAssets[$logo->assetable_id] = array('logo' => $logo->path, 'about' => '');
                    }
                }

                foreach($abouts as $about)
                {
                    if(isset($aAssets[$about->assetable_id]))
                    {
                        $aAssets[$about->assetable_id]['about'] = $about->path;
                    }
                    else
                    {
                        $aAssets[$about->assetable_id] = array('about' => $about->path, 'logo' => '');
                    }
                }

                $return = array('objects' => array(), 'stats' => array('total' => count($entities), 'take' => count($entities), 'page' => 0, 'returned' => count($entities)));
                foreach($entities as $entity)
                {
                    $entity->is_clipped = 0;
                    $entity->logo = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['logo'] : '';
                    $entity->about_img = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['about'] : '';
                    $return['objects'][] = $entity;
                }

                $aFiller = $entities;
                break;
            
            default:
                $filler = new \SOE\DB\Entity;
                $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/sohi_ad.jpg';
                $filler->url = $sohi ? '/homeimprovement' : 'http://saveonhomeimprovement.com';
                $filler->entitiable_type = 'filler';
                $aFiller[] = $filler;
                $filler = new \SOE\DB\Entity;
                $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/soct_ad.jpg';
                $filler->url = '/cars';
                $filler->entitiable_type = 'filler';
                $aFiller[] = $filler;
                $filler = new \SOE\DB\Entity;
                $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg';
                $filler->url = 'http://saveongroceries.com';
                $filler->entitiable_type = 'filler';
                $aFiller[] = $filler;
                break;
        }
        return $aFiller;
    }

    /**
     * Update entities when a merchant is updated based on the franchise, merchant, and logo.
     *
     * @param object $franchise
     * @param object $merchant
     * @param object $logo
     * @return void
     */
    public function updateMerchant($franchise, $merchant, $logo)
    {
        $category = \SOE\DB\Category::find($merchant->category_id);
        $subcategory = \SOE\DB\Category::find($merchant->subcategory_id);
        \DB::table('entities')->join('offers', function($join)
            {
                $join->on('entities.entitiable_id', '=', 'offers.id');
                $join->on('entities.entitiable_type', '=', \DB::raw("'Offer'"));
            })->where('offers.franchise_id', $franchise->id)->update(array(
            'offers.is_active' => ($franchise->is_active && $merchant->is_active) ? 1 : 0,
            'entities.category_id' => $merchant->category_id,
            'entities.subcategory_id' => $merchant->subcategory_id,
            'entities.path' => $logo->path,
            'entities.is_active' => ($franchise->is_active && $merchant->is_active) ? 1 : 0,
            'entities.merchant_slug' => $merchant->slug,
            'entities.merchant_name' => $merchant->name,
            'entities.franchise_active' => $franchise->is_active,
            'entities.category_slug' => $category->slug,
            'entities.subcategory_slug' => $subcategory->slug,
            'entities.updated_at' => date('Y-m-d H:i:s'),
            'offers.updated_at' => date('Y-m-d H:i:s')
        ));

        \DB::table('entities')->join('contests', function($join)
            {
                $join->on('entities.entitiable_id', '=', 'contests.id');
                $join->on('entities.entitiable_type', '=', \DB::raw("'Contest'"));
            })->where('contests.franchise_id', $franchise->id)->update(array(
            'contests.is_active' => ($franchise->is_active && $merchant->is_active) ? 1 : 0,
            'entities.category_id' => $merchant->category_id,
            'entities.subcategory_id' => $merchant->subcategory_id,
            'entities.is_active' => ($franchise->is_active && $merchant->is_active) ? 1 : 0,
            'entities.merchant_slug' => $merchant->slug,
            'entities.merchant_name' => $merchant->name,
            'entities.franchise_active' => $franchise->is_active,
            'entities.category_slug' => $category->slug,
            'entities.subcategory_slug' => $subcategory->slug,
            'entities.updated_at' => date('Y-m-d H:i:s'),
            'contests.updated_at' => date('Y-m-d H:i:s')
        ));
    }

    public function updateLocation($location_id)
    {
        $location = \SOE\DB\Location::withTrashed()->find($location_id);
        $this->query()->where('location_id', $location_id)
                    ->where('merchant_id', $location->merchant_id)
                    ->update(array(
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'latm' => $location->latm,
                        'lngm' => $location->lngm,
                        'state' => $location->state,
                        'location_active' => $location->is_active
                    ));

        $offers = \SOE\DB\Offer::where('franchise_id', $location->franchise_id)
                                ->where('expires_at', '>', date('Y-m-d H:i:s'))
                                ->where('is_active', '1')
                                ->where('is_location_specific', '0')
                                ->get();
        foreach($offers as $offer)
        {
            $existing = $this->query()->where('entitiable_id', $offer->id)
                                    ->where('entitiable_type', 'Offer')
                                    ->where('location_id', $location_id)
                                    ->first();
            if(!$existing)
            {
                $this->createFromOfferLocation($offer, $location);
            }
        }
    }

    public function updateOffer($offer_id, $location_ids = array())
    {
        $offer = \SOE\DB\Offer::withTrashed()->find($offer_id);
        $merchant = \SOE\DB\Merchant::find($offer->merchant_id);
        $logo = \SOE\DB\Asset::where('assetable_id', $merchant->id)
                            ->where('assetable_type', 'Merchant')
                            ->where('name', 'logo1')
                            ->first();
        $this->query()->where('entitiable_id', $offer_id)
                    ->where('entitiable_type', 'Offer')
                    ->update(array(
                        'name' => $offer->name,
                        'slug' => $offer->slug,
                        'is_active' => $offer->is_active,
                        'path' => $offer->path ? $offer->path : ($logo ? $logo->path : ''),
                        'regular_price' => $offer->regular_price,
                        'special_price' => $offer->special_price,
                        'is_dailydeal' => $offer->is_dailydeal,
                        'starts_at' => $offer->starts_at,
                        'expires_at' => $offer->expires_at,
                        'starts_year' => date('Y', strtotime($offer->starts_at)),
                        'starts_day' => date('z', strtotime($offer->starts_at)),
                        'expires_year' => date('Y', strtotime($offer->expires_at)),
                        'expires_day' => date('z', strtotime($offer->expires_at))
                    ));

        if(empty($location_ids))
        {
            $locations = \SOE\DB\Location::where('franchise_id', $offer->franchise_id)
                                        ->where('is_active', '1')
                                        ->get();
            foreach($locations as $location)
            {
                $existing = \SOE\DB\Entity::where('location_id', $location->id)
                                        ->where('entitiable_type', 'Offer')
                                        ->where('entitiable_id', $offer->id)
                                        ->first();
                if(!$existing)
                    $this->createFromOfferLocation($offer, $location);
            }
        }
        else
        {
            // Create new entities
            foreach($location_ids as $loc_id)
            {
                $existing = \SOE\DB\Entity::where('location_id', $loc_id)
                                        ->where('entitiable_type', 'Offer')
                                        ->where('entitiable_id', $offer->id)
                                        ->first();
                if(!$existing)
                {
                    $location = \SOE\DB\Location::find($loc_id);
                    $this->createFromOfferLocation($offer, $location);
                }
            }

            // Delete old entities that are no longer linked to a location
            \SOE\DB\Entity::whereNotIn('location_id', $location_ids)
                        ->where('entitiable_id', $offer->id)
                        ->where('entitiable_type', 'Offer')
                        ->delete();
        }
    }

    /**
     * Create an entity from the given offer and location.
     *
     * @param object $offer
     * @param object $location
     * @return mixed
     */
    public function createFromOfferLocation($offer, $location)
    {
        $merchant = \SOE\DB\Merchant::join(\DB::raw('categories as cat'), 'merchants.category_id', '=', 'cat.id')
                                    ->join(\DB::raw('categories as subcat'), 'merchants.subcategory_id', '=', 'subcat.id')
                                    ->where('merchants.id', $location->merchant_id)
                                    ->first(array(
                                        'merchants.*', 
                                        \DB::raw('cat.slug as cat_slug'), 
                                        \DB::raw('subcat.slug as subcat_slug')
                                    ));
        $company = \SOE\DB\Company::find($location->company_id);
        if(!$merchant || !$company)
            return false;
        $logo = \SOE\DB\Asset::where('assetable_id', $location->merchant_id)
                            ->where('assetable_type', 'Merchant')
                            ->where('name', 'logo1')
                            ->first();
        return $this->create(array(
            'entitiable_id' => $offer->id,
            'entitiable_type' => 'Offer',
            'name' => $offer->name,
            'slug' => $offer->slug,
            'location_id' => $location->id,
            'category_id' => $merchant->category_id,
            'subcategory_id' => $merchant->subcategory_id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'path' => $offer->path ? $offer->path : ($logo ? $logo->path : ''),
            'is_dailydeal' => $offer->is_dailydeal,
            'rating' => 3.5,
            'special_price' => $offer->special_price,
            'regular_price' => $offer->regular_price,
            'is_demo' => $offer->is_demo,
            'is_active' => $offer->is_active,
            'starts_at' => $offer->starts_at,
            'expires_at' => $offer->expires_at,
            'rating_count' => 0,
            'savings' => $offer->savings,
            'url' => $offer->url,
            'print_override' => $offer->print_override,
            'secondary_type' => '',
            'latm' => $location->latm,
            'lngm' => $location->lngm,
            'merchant_id' => $location->merchant_id,
            'merchant_slug' => $merchant->slug,
            'merchant_name' => $merchant->display,
            'popularity' => 0,
            'is_featured' => 0,
            'state' => $location->state,
            'expires_year' => date('Y', strtotime($offer->expires_at)),
            'expires_day' => date('z', strtotime($offer->expires_at)),
            'starts_year' => date('Y', strtotime($offer->starts_at)),
            'starts_day' => date('z', strtotime($offer->starts_at)),
            'category_slug' => $merchant->cat_slug,
            'subcategory_slug' => $merchant->subcat_slug,
            'company_id' => $location->company_id,
            'company_name' => $company->name
        ));   
    }

    /**
     * Generate the not expired mysql query parts.
     *
     * @param Illuminate\Database\Query\Builder
     * @return Illuminate\Database\Query\Builder
     */
    protected function queryNotExpired($query)
    {
        return $query->where(function($query)
                    {
                        $query->where('entities.starts_year', '=', date('Y'));
                        $query->where('entities.starts_day', '<=', (date('z')+1));
                        $query->orWhere('entities.starts_year', '<', (date('Y')));
                    })
                    ->where(function($query)
                    {
                        $query->where('entities.expires_year', '=', date('Y'));
                        $query->where('entities.expires_day', '>=', (date('z')+1));
                        $query->orWhere('entities.expires_year', '>=', (date('Y')+1));
                    });
    }

    /**
     * Generate the category mysql filters.
     *
     * @param Illuminate\Database\Query\Builder
     * @param mixed     $category The category slug or id.
     * @param mixed     $subcategory The subcategory slug or id.
     * @return Illuminate\Database\Query\Builder
     */
    protected function queryCategoryFilters($query, $category = '', $subcategory = '')
    {
        if(is_numeric($category))
            $category = $this->categoryRepository->find($category);
        else
            $category = $this->categoryRepository->findBySlug($category);

        if(is_numeric($subcategory))
            $subcategory = $this->categoryRepository->find($subcategory);
        else
            $subcategory = $this->categoryRepository->findBySlug($subcategory);

        if($category)
            $query = $query->where('category_id', '=', $category->id);
        if($subcategory)
            $query = $query->where('subcategory_id', '=', $subcategory->id);

        return $query;
    }

    /**
     * Set the popularity for every entity back to 0.
     *
     * @return void
     */
    public function resetPopularity()
    {
        \DB::table('entities')->update(array('popularity' => 0));
    }

    /**
     * Add a popularity score to entites matching the given offer id.
     *
     * @param float     $popularity A popularity score.
     * @param int       $offer_id The id of the offer.
     * @return void
     */
    public function addOfferPopularity($popularity, $offer_id)
    {
        \SOE\DB\Entity::where('entitiable_type', '=', 'Offer')
                    ->where('entitiable_id', '=', $offer_id)
                    ->update(array(
                        'popularity' => \DB::raw('`popularity` + '.$popularity)
                    ));
    }

    /**
     * Add a popularity score to entites matching the given offer id.
     *
     * @param float     $popularity A popularity score.
     * @param int       $offer_id The id of the offer.
     * @return void
     */
    public function addMerchantPopularity($popularity, $merchant_id)
    {
        \SOE\DB\Entity::where('merchant_id', '=', $merchant_id)
                    ->update(array(

                        'popularity' => \DB::raw('`popularity` + '.$popularity)
                    ));
    }

    /**
     * Add locations to an array of entity objects.
     *
     * @param array $entities
     * @return array
     */
    public function addLocations($entities)
    {
        foreach($entities as &$entity)
        {
            $entity->location = \SOE\DB\Location::find($entity->location_id);
        }
        return $entities;
    }
}


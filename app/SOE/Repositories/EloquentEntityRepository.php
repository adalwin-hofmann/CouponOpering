<?php

class EloquentEntityRepository extends BaseEloquentRepository implements EntityRepository, ShareableInterface, ViewableInterface, RepositoryInterface
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
        'is_national',
        'project_tags',
        'is_certified',
        'stuffing_priority',
        'is_sohi_trial',
        'sohi_trial_starts_at',
        'sohi_trial_ends_at',
        'service_radius',
        'short_name_line1',
        'short_name_line2',
        'category_visible',
        'hide_expiration',
        'is_reoccurring',
        'merchant_logo',
    );

    protected $model = 'Entity';

    /**
     * *DEPRECATED* Retrieve a Recommendations for a given Person.
     *
     * @param  PersonInterface  $person
     * @param  array            $geoip_data Array of geoip data to use in search (optional).
     * @return mixed
     */
    public function getRecommendations(PersonInterface $person, $geoip_data = array(), $limit = 0)
    {
        /**
        *This is our method for ranking categories.  It is based on user preferences in the members section.
        *There is a preference intact for every one of our major categories.
        *In the /members/my interests page, the default setting is to have all categories selected.
        *Users must manually uncheck categories.
        *In the remaining checked categories, each member is assigned a score for each category.
        *This score is based on the number of prints and views the user has in each category. (Behavioral recommendation)
        *The categories are then ranked according to the $aGroupings array.
        *A ranking of 40 for the most-liked category, 25 for the second, and so on.
        *Then, suggestions are made, grabbing entities from the DB according to the array rankings.
        *They are also selected according to popularity (other users' clips, prints, likes, dislikes, reviews)
        *These orders are shuffled, then displayed in a random order.
        *For nonmembers, there is a similar process, but user activity and preference is not a factor.
        *Grabs 40, 25, 15, 10, 5, 3, and 2 popular offers for random categories based on locations.
        */

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
        if(Config::get('cache.driver') != 'file' && $person->getType() == 'User')
        {
            //Lots of errors
            //if(Cache::tags('recommendations', $person->getType())->has($person->id))
            //    return Cache::tags('recommendations', $person->getType())->get($person->id);
        }
        $geoip = empty($geoip_data) ? json_decode(GeoIp::getGeoIp('json')) : $geoip_data;

        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        //$zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        $zipcode = SOE\DB\Zipcode::where('state', '=', $geoip->region_name)
                                ->where('city', '=', $geoip->city_name)
                                ->where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                })
                                ->where($zip_distance, '<', 68000)// Remove times when accuracy is not great
                                ->orderBy('estimatedpopulation', 'desc')
                                ->first(array('zipcodes.*'));
        $geoip->latitude = $zipcode->latitude;
        $geoip->longitude = $zipcode->longitude;
        $geoip->region_name = $zipcode->state;
        $geoip->city_name = $zipcode->city;

        $cartesian = array('latm' => $zipcode->latm, 'lngm' => $zipcode->lngm);
        $distance = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $ranks = $person->getRankings();
        $preferences = $person->getPreferences();
        arsort($ranks);
        $i=0;
        $entities = array();
        $aIDs = array(0);

        //Get featured entities to stuff
        $stuff_config = SOE\DB\Feature::where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'recommendation_stuffing')->remember(Config::get('soe.cache', 60*60*24))->first();
        $stuff_config = empty($stuff_config) ? 0 : $stuff_config->value;
        $featured = SOE\DB\Entity::where('state', '=', $geoip->region_name)
                                ->where(function($query)
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
                                })
                                ->where('is_active', '=', '1')
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->where('is_featured', '=', '1')
                                ->groupBy('location_id')
                                ->orderBy(DB::raw('RAND()'))
                                ->take($stuff_config)
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get();
        foreach($featured as $feature)
        {
            array_push($aIDs, $feature->id);
        }
        $aString = "((0,'Offer'),";
        foreach($featured as $feat)
        {
            $aString .= "(".$feat->entitiable_id.",'".$feat->entitiable_type."'),";
        }
        $aString = rtrim($aString, ',');
        $aString .= ')';

        $aStates = Zipcode::getSurroundingStates($geoip->latitude, $geoip->longitude);

        $radius = Feature::findByName('recommendation_dist');
        $radius = empty($radius) ? 34000 : $radius->value;
        foreach($ranks as $slug => $rank)
        {
            if($preferences[$slug] == 0)
            {
                continue;
            }
            $query = DB::table('entities')->where($distance, '<', $radius) //About 20 miles
                                        ->whereIn('state', $aStates)// '=', $geoip->region_name)
                                        ->where('category_id', '=', $aCategories[$slug])
                                        ->where(function($query)
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
                                        })
                                        ->whereRaw('(entitiable_id, entitiable_type) NOT IN '.$aString)
                                        ->where('is_featured', '=', '0')
                                        ->where('location_active', '=', '1')
                                        ->where('franchise_active', '=', '1')
                                        ->where('is_active', '=', '1');
            if($person->showDemo() == false)
            {
                $query = $query->where('is_demo', '=', '0')
                                ->where('franchise_demo', '=', '0');
            }
            $entities[] = $query->orderBy('popularity', 'desc')
                                ->groupBy('merchant_id')
                                ->take($aGroupings[$i])
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get(array('id', 'entitiable_id', 'entitiable_type', 'location_id'));
            $i++;
        }

        $aRecIDs = array(0);
        $aLocIDs = array(0);
        $aEntIDs = array(0);
        $aComposite = array(array(0,'Offer'));
        foreach($entities as $entity)
        {
            foreach($entity as $e)
            {
                $aLocIDs[] = $e->location_id;
                $aRecIDs[] = $e->id;
                $aComposite[] = array($e->entitiable_id, $e->entitiable_type);
                if($e->entitiable_type == 'Offer')
                {
                    $aEntIDs[] = $e->entitiable_id;
                }
            }
        }

        $counts_query = SOE\DB\Entity::whereIn('state', $aStates)->whereIn('location_id', $aLocIDs);

        if($person->showDemo() == false)
        {
            $counts_query = $counts_query->where('is_demo', '=', '0')
                                        ->where('franchise_demo', '=', '0');
        }
        $counts = $counts_query->where(function($query)
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
                                })
                                ->where('is_active', '=', '1')
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->groupBy('location_id')
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get(array('location_id', DB::raw('COUNT(*) as total_entities')));
        $aCounts = array();
        foreach($counts as $count)
        {
            $aCounts[$count->location_id] = $count->total_entities;
        }

        $aClipIDs = array(0);
        if($person->getType() == 'User')
        {
            $clips = SOE\DB\UserClipped::whereIn('offer_id', $aEntIDs)
                                        ->where('user_id', '=', $person->id)
                                        ->where('is_deleted', '=', '0')
                                        ->get(array('offer_id'));
            foreach($clips as $clip)
            {
                $aClipIDs[] = $clip->offer_id;
            }
        }

        shuffle($aRecIDs);

        $entity_filters = array();
        $entity_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $aIDs);
        $featured_results = Entity::get($entity_filters);

        $entity_filters = array();
        $entity_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $aRecIDs);
        $rec_results = Entity::get($entity_filters);

        $results = array('objects' => array());
        $results['objects'] = array_merge($featured_results['objects'], $rec_results['objects']);
        $results['stats'] = array('total' => count($results['objects']), 'page' => 0, 'take' => 0, 'returned' => count($results['objects']));

        if($limit != 0)
        {
            $copy_results = $rec_results;
            shuffle($copy_results['objects']);
            $rec_results['objects'] = array();
            for($i = 0; $i < $limit && $i < $copy_results['stats']['returned']; $i++)
            {
                $rec_results['objects'][] = $copy_results['objects'][$i];
            }
            $rec_results['stats']['returned'] = $limit > $rec_results['stats']['returned'] ? $rec_results['stats']['returned'] : $limit;
            $rec_results['stats']['limit'] = $limit;
            foreach($rec_results['objects'] as $ent)
            {
                $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
                $ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
            }
            return $rec_results;
        }

        foreach($results['objects'] as $ent)
        {
            $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
            $ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
        }
        if(Config::get('cache.driver') != 'file' && $person->getType() == 'User')
        {
            //Lots of errors
            //Cache::tags('recommendations', $person->getType())->put($person->id, $results, 30);
        }
        return $results;
    }

    /**
     * *DEPRECATED* Retrieve entities for by type and category/subcategory for the given person.
     *
     * @param  PersonInterface  $person
     * @param  string           $city City to find results in
     * @param  string           $state State to find results in
     * @param  float            $lat Latitude of area to find results.
     * @param  float            $lng Longitude of area to find results.
     * @param  string           $type Default is all types, can be 'Offer', 'Contest'.
     * @param  int              $category_id Default is all categories.
     * @param  string           $sort Default is 'nearest', can be 'furthest', 'popular', 'az', 'za'.
     * @param  int              $page Page number, default is 0.
     * @param  int              $limit Number of results to return, default is 12.
     * @param  int              $radius Radius to search for results in meters.
     * @return mixed
     */
    public function getByCategory(PersonInterface $person, $city, $state, $lat, $lng, $type = null, $category_id = null, $sort = 'nearest', $page = 0, $limit = 12, $radius = 0)
    {
        $category = Category::find($category_id);
        $cartesian = SoeHelper::getCartesian($lat, $lng);

        // calculate the distance between the person and the entity
        $zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        $category_distance = Feature::findByName('category_dist');
        $category_distance = empty($category_distance) ? 34000 : $category_distance->value;
        $zipcode = SOE\DB\Zipcode::where('state', '=', $state)
            ->where('city', '=', $city)
            ->where('zipcodetype', '=', 'STANDARD')
            ->where(function($query) {
                $query->where('locationtype', '=', 'PRIMARY');
                $query->orWhere('locationtype', '=', 'ACCEPTABLE');
            })
            ->where($zip_distance, '<', $category_distance)
            ->orderBy('estimatedpopulation', 'desc')
            ->first(array('zipcodes.*'));

        // TODO: what happens if the zipcode is empty / no results? (exception)
        // obj doesn't exist
        //
        $cartesian = array('latm' => $zipcode->latm, 'lngm' => $zipcode->lngm);

        $distance_col = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
        $distance = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');

        // TODO: don't hardcode, use constant
        // Search for entities within an X feet? radius 34k feet is 6.4 miles
        $aStates = Zipcode::getSurroundingStates($lat, $lng);

        $query = SOE\DB\Entity::whereIn('state', $aStates)
            ->where($distance, '<', ($radius == 0 ? $category_distance : $radius)); //About 10 miles

        switch(strtolower($type)) {
            case 'coupon':
                $query = $query->where('entitiable_type', '=', 'Offer')->where('is_dailydeal', '=', '0');
                break;
            case 'dailydeal':
                $query = $query->where('entitiable_type', '=', 'Offer')->where('is_dailydeal', '=', '1');
                break;
            case 'contest':
                $query = $query->where('entitiable_type', '=', 'Contest');
                break;
            default:
                break;
        }

        // what?
        // TODO: refactor this so that the variable is the value of the field
        // If the category is the parent, use the category_id, otherwise use subcategory_id
        if(empty($category)) {
            $query = $query;
        } else if($category->parent_id == 0) {
            $query = $query->where(function($query) use ($category_id) {
                    $query->where('category_id', '=', $category_id);
                    $query->orWhere('category_id', '=', '0');
                });
        } else {
            $query = $query->where(function($query) use ($category_id) {
                    $query->where('subcategory_id', '=', $category_id);
                    $query->orWhere('subcategory_id', '=', '0');
                });
        }

        // Demo Settings
        if($person->showDemo() == false) {
            $query = $query->where('is_demo', '=', '0')
                ->where('franchise_demo', '=', '0');
        }

        // Get ids of all entity children that have started already and haven't expired yet
        // TODO: don't use >= or <= with +1, just use > and <
        // TODO: make the cache time a constant
        $sub_entities = $query
            ->where(function($query) {
                $query->where('starts_year', '=', date('Y'));
                $query->where('starts_day', '<=', (date('z')+1));
                $query->orWhere('starts_year', '<', (date('Y')));
            })
            ->where(function($query) {
                $query->where('expires_year', '=', date('Y'));
                $query->where('expires_day', '>=', (date('z')+1));
                $query->orWhere('expires_year', '>=', (date('Y')+1));
            })
            ->where('is_active', '=', '1')
            ->where('location_active', '=', '1')
            ->where('franchise_active', '=', '1')
            ->groupBy('entitiable_id')
            ->remember(Config::get('soe.cache', 60*60*24))
            ->get(array('id'));

        $aSubIDs = array(0);

        // Save all the children's ids into an array
        foreach($sub_entities as $se) {
            $aSubIDs[] = $se->id;
        }
        // TODO: subselect instead maybe?
        $query = SOE\DB\Entity::whereIn('entities.id', $aSubIDs)
            ->groupBy('entities.location_id');
        $stats = $this->getStats(clone $query, $limit, $page,true);

        // Aggregate select parameters
        $get = array();
        // TODO: use if
        switch(strtolower($type)) {
            case 'coupon':
            case 'dailydeal':
                $query = $query
                    ->join('offers', 'entities.entitiable_id', '=', 'offers.id')
                    ->orderBy('is_yipit', 'asc');
                $get[] = DB::raw("IF(offers.yipitdeal_id = 0, 0, 1) as is_yipit");
                break;
            default:
                break;
        }

        // Determine the sort order
        switch ($sort) {
            case 'nearest':
                $query = $query->orderBy('distance');
                break;
            case 'furthest':
                $query = $query->orderBy('distance', 'desc');
                break;
            case 'popular':
                // TODO: create a metric for popular entities
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
        $get[] = $distance_col;

        // TODO: use cache time const
        // Submit the entity query
        $entities = $query
            ->take($limit)
            ->skip($limit*$page)
            ->remember(Config::get('soe.cache', 60*60*24))
            ->get($get);

        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        $aIDs = array(0);
        $aEntIDs = array(0);
        foreach($entities as $entity) {
            $aIDs[] = $entity->location_id;
            if($entity->entitiable_type == 'Offer') {
                $aEntIDs[] = $entity->entitiable_id;
            }
        }

        $aClipIDs = array(0);
        if($person->getType() == 'User') {
            $clips = SOE\DB\UserClipped::whereIn('offer_id', $aEntIDs)
                ->where('user_id', '=', $person->id)
                ->where('is_deleted', '=', '0')
                ->get(array('offer_id'));

            foreach($clips as $clip) {
                $aClipIDs[] = $clip->offer_id;
            }
        }
        $counts_query = SOE\DB\Entity::whereIn('state', $aStates)->whereIn('location_id', $aIDs);

        // TODO: move this with the other demo code
        if($person->showDemo() == false) {
            $counts_query = $counts_query
                ->where('is_demo', '=', '0')
                ->where('franchise_demo', '=', '0');
        }

        // TODO: these date queries look the same as above
        // Count the number of results from the active entities?
        $counts = $counts_query
            ->where(function($query) {
                $query->where('starts_year', '=', date('Y'));
                $query->where('starts_day', '<=', (date('z')+1));
                $query->orWhere('starts_year', '<', (date('Y')));
            })
            ->where(function($query) {
                $query->where('expires_year', '=', date('Y'));
                $query->where('expires_day', '>=', (date('z')+1));
                $query->orWhere('expires_year', '>=', (date('Y')+1));
            })
            ->where('is_active', '=', '1')
            ->where('location_active', '=', '1')
            ->where('franchise_active', '=', '1')
            ->groupBy('location_id')
            ->remember(Config::get('soe.cache', 60*60*24))
            ->get(array('location_id', DB::raw('COUNT(*) as total_entities')));

        $aCounts = array();
        foreach($counts as $count) {
            $aCounts[$count->location_id] = $count->total_entities;
        }

        // Turn models into repositories
        foreach($entities as $entity) {
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
            $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
            $results['objects'][] = $ent;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Retrieve similar entities for the given person and merchant.
     *
     * @param  MerchantRepository   $merchant_id
     * @param  PersonInterface      $person
     * @param  float                $lat Latitude of area to find results.
     * @param  float                $lng Longitude of area to find results.
     * @return mixed
     */
    public function getSimilar(MerchantRepository $merchant, PersonInterface $person)
    {
        $limit = 3;
        $page = 0;
        $category = Category::find($merchant->category_id);
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $distance_col = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
        $distance = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $radius = Feature::findByName('banner_radius');
        $radius = empty($radius) ? 34000 : $radius->value;
        $query = SOE\DB\Entity::where($distance, '<', Config::get('soe.distance', '34000')); //About 10 miles
        if(empty($category))
        {
            $query = $query;
        }
        else if($category->parent_id == 0)
        {
            $query = $query->where(function($query) use ($category)
                {
                    $query->where('category_id', '=', $category->id);
                    $query->orWhere('category_id', '=', '0');
                });
        }
        else
        {
            $query = $query->where(function($query) use ($category)
                {
                    $query->where('subcategory_id', '=', $category->id);
                    $query->orWhere('subcategory_id', '=', '0');
                });
        }
        if($person->showDemo() == false)
        {
            $query = $query->where('is_demo', '=', '0')
                            ->where('franchise_demo', '=', '0');
        }
        $sub_entities = $query->where('starts_at', '<', DB::raw('NOW()'))
                            ->where('expires_at', '>', DB::raw('NOW()'))
                            ->where('is_active', '=', '1')
                            ->where('location_active', '=', '1')
                            ->where('franchise_active', '=', '1')
                            ->where('merchant_id', '!=', $merchant->id)
                            ->groupBy('entitiable_id')
                            ->remember(Config::get('soe.cache', 60*60*24))
                            ->get(array('id'));
        $aSubIDs = array(0);
        foreach($sub_entities as $se)
        {
            $aSubIDs[] = $se->id;
        }
        $query = SOE\DB\Entity::whereIn('id', $aSubIDs)
                            ->groupBy('location_id');
        $stats = $this->getStats(clone $query, $limit, $page);
        $entities = $query->take($limit)->skip($limit*$page)
                        ->get(array(
                            'entities.*',
                            $distance_col
                        ));
        $stats['stats']['returned'] = count($entities);
        $results = array('objects' => array());
        $aIDs = array(0);
        foreach($entities as $entity)
        {
            $aIDs[] = $entity->location_id;
        }
        $counts = SOE\DB\Entity::whereIn('location_id', $aIDs)
                                ->where('starts_at', '<', DB::raw('NOW()'))
                                ->where('expires_at', '>', DB::raw('NOW()'))
                                ->where('is_active', '=', '1')
                                ->where('location_active', '=', '1')
                                ->where('franchise_active', '=', '1')
                                ->groupBy('location_id')
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get(array('location_id', DB::raw('COUNT(*) as total_entities')));
        $aCounts = array();
        foreach($counts as $count)
        {
            $aCounts[$count->location_id] = $count->total_entities;
        }
        foreach($entities as $entity)
        {
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $ent->total_entities = isset($aCounts[$ent->location_id]) ? $aCounts[$ent->location_id] : 0;
            $results['objects'][] = $ent;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * *DEPRECATED* Get a collection of featured entities.
     *
     * @param PersonInterface   $person The person to get entities for.
     * @param string            $type The type of entities to retrieve.
     * @param string            $state The state to look in.
     * @param float             $latitude The latitude to look around.
     * @param float             $longitude The longitude to look around.
     * @return mixed
     */
    public function getFeatured(PersonInterface $person, $type, $state, $latitude, $longitude, $radius = 0, $category_slug = 'all', $subcategory_slug = '')
    {
        $cartesian = SoeHelper::getCartesian($latitude, $longitude);
        $distance = DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        $ads_feature = SOE\DB\Feature::where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'show_ads')->first();
        $ads_feature = empty($ads_feature) ? 0 : $ads_feature->value;
        $aStates = Zipcode::getSurroundingStates($latitude, $longitude);
        switch (strtolower($type))
        {
            case 'offer':
                $query = SOE\DB\Entity::whereIn('state', $aStates)
                                        ->where('is_featured', '=', '1')
                                        ->where($distance, '<', Config::get('soe.distance', '34000'))
                                        ->where(function($query) use ($ads_feature)
                                        {
                                            $query->where('entitiable_type', '=', 'Offer');
                                            if($ads_feature)
                                                $query->orWhere('entitiable_type', '=', 'Ad');
                                        })
                                        ->where(function($query)
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
                                        })
                                        ->where('is_dailydeal', '=', '0')
                                        ->where('location_active', '=', '1')
                                        ->where('franchise_active', '=', '1')
                                        ->where('is_active', '=', '1');
                if($category_slug != 'all') {
                    $category = Category::findBySlug($category_slug);
                    if ($category) {
                        $query = $query->where('category_id', $category->id);
                    }
                }
                if($person->showDemo() == false)
                {
                    $query = $query->where('is_demo', '=', '0')
                                    ->where('franchise_demo', '=', '0');
                }
                $entities = $query->orderBy(DB::raw('RAND()'))
                                ->take(3)
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get();
                break;
            case 'dailydeal':
                $query = SOE\DB\Entity::whereIn('state', $aStates)
                                        ->where('is_featured', '=', '1')
                                        ->where($distance, '<', Config::get('soe.distance', '34000'))
                                        ->where(function($query) use ($ads_feature)
                                        {
                                            $query->where('entitiable_type', '=', 'Offer');
                                            if($ads_feature)
                                                $query->orWhere('entitiable_type', '=', 'Ad');
                                        })
                                        ->where(function($query)
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
                                        })
                                        ->where('is_dailydeal', '=', '1')
                                        ->where('location_active', '=', '1')
                                        ->where('franchise_active', '=', '1')
                                        ->where('is_active', '=', '1');
                if($category_slug != 'all') {
                    $category = Category::findBySlug($category_slug);
                    if ($category) {
                        $query = $query->where('category_id', $category->id);
                    }
                }
                if($person->showDemo() == false)
                {
                    $query = $query->where('is_demo', '=', '0')
                                    ->where('franchise_demo', '=', '0');
                }
                $entities = $query->orderBy(DB::raw('RAND()'))
                                ->take(3)
                                ->remember(Config::get('soe.cache', 60*60*24))
                                ->get();
                break;
            case 'contest':
                $query = SOE\DB\Entity::whereIn('state', $aStates)
                                        ->where('is_featured', '=', '1')
                                        ->where($distance, '<', ($radius == 0 ? Config::get('soe.distance', '34000') : $radius))
                                        ->where(function($query) use ($ads_feature)
                                        {
                                            $query->where('entitiable_type', '=', 'Contest');
                                            if($ads_feature)
                                                $query->orWhere('entitiable_type', '=', 'Ad');
                                        })
                                        ->where(function($query)
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
                                        })
                                        ->where('is_active', '=', '1');
                if($person->showDemo() == false)
                {
                    $query = $query->where('is_demo', '=', '0');
                }
                $entities = $query->orderBy(DB::raw('RAND()'))
                                ->take(3)
                                //->remember(Config::get('soe.cache', 60*60*24))
                                ->get();
                break;
        }
        $aEntIDs = array(0);
        $aMerchIDs = array(0);
        foreach($entities as $entity)
        {
            $aMerchIDs[] = $entity->merchant_id;
            if($entity->entitiable_type == 'Offer')
                $aEntIDs[] = $entity->entitiable_id;
        }
        $clips = SOE\DB\UserClipped::whereIn('offer_id', $aEntIDs)
                                    ->where('is_deleted', '=', '0')
                                    ->where('user_id', '=', ($person->getType() == 'User' ? $person->id : '0'))
                                    ->get();
        $aClipIDs = array();
        foreach($clips as $clip)
        {
            $aClipIDs[] = $clip->offer_id;
        }
        $logos = SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                            ->where('assetable_type', '=', 'Merchant')
                            ->where('name', '=', 'logo1')
                            ->remember(Config::get('soe.cache', 60*60*24))
                            ->get();
        $abouts = SOE\DB\Asset::whereIn('assetable_id', $aMerchIDs)
                            ->where('assetable_type', '=', 'Merchant')
                            ->where('name', 'LIKE', 'smallImage%')
                            ->groupBy('assetable_id')
                            ->remember(Config::get('soe.cache', 60*60*24))
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
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
            $ent->logo = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['logo'] : '';
            $ent->about_img = isset($aAssets[$entity->merchant_id]) ? $aAssets[$entity->merchant_id]['about'] : '';
            $return['objects'][] = $ent;
        }
        $sohi = Feature::findByName('home_improvement');
        $sohi = empty($sohi) ? 0 : $sohi->value;
        $aFiller = array();
        $filler = Entity::blank();
        $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/sohi_ad.jpg';
        $filler->url = $sohi ? '/homeimprovement' : 'http://saveonhomeimprovement.com';
        $filler->entitiable_type = 'filler';
        $aFiller[] = $filler;
        $filler = Entity::blank();
        $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/soct_ad.jpg';
        $filler->url = 'http://saveoncarsandtrucks.com';
        $filler->entitiable_type = 'filler';
        $aFiller[] = $filler;
        $filler = Entity::blank();
        $filler->path = 'http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg';
        $filler->url = 'http://saveongroceries.com';
        $filler->entitiable_type = 'filler';
        $aFiller[] = $filler;

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

        return $return;
    }

    /**
     * View this Entity for a given person.
     *
     * @param PersonInterface $viewer
     */
    public function view(PersonInterface $viewer)
    {
        if($this->primary_key)
        {
            $viewerType = $viewer->getType();
            if($this->entitiable_type == 'Contest')
            {
                $contest = Contest::find($this->entitiable_id);
                $view = UserImpression::blank();
                $view->entity = $this;
                $view->contest = $contest;
                if($viewerType == 'User')
                    $application = SOE\DB\ContestApplication::where('contest_id', '=', $this->entitiable_id)->where('user_id', '=', $viewer->id)->first();
                else
                    $application = array();
                $view->is_entered = empty($application) ? 0 : 1;
                if($this->secondary_type == 'internal' || $this->secondary_type == 'external')
                {
                    $randomnum = Contest::getSweepstakes($this);
                    $view->randomnum = $randomnum;
                }
                return $view;
            }
            $offer = Offer::find($this->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$this->company_id)->first();
            $franchise = Franchise::find($offer->franchise_id);
            $location = Location::find($this->location_id);
            $view = UserImpression::blank();
            $view->user_id = $viewerType == 'User' ? $viewer->id : 0;
            $view->nonmember_id = $viewerType != 'User' ? $viewer->id : 0;
            $view->entity_id = $this->id;
            $view->offer_id = $offer->id;
            $view->location_id = $this->location_id;
            $view->merchant_id = $this->merchant_id;
            $view->save();
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $view_id = $view->id;
            if(\App::environment() == 'prod') {
                Queue::push(function($job) use ($view_id, $geoip)
                {
                    $view = UserImpression::find($view_id);
                    $person = $view->user_id != 0 ? User::find($view->user_id) : Nonmember::find($view->nonmember_id);
                    $entity = Entity::find($view->entity_id);
                    if(empty($person) || empty($entity))
                    {
                        $job->delete();
                        return;
                    }
                    $identity = $view->user_id != 0 ? $person->email : 'non-'.$view->nonmember_id;
                    $location = Location::find($entity->location_id);
                    $merchant = Merchant::find($entity->merchant_id);
                    $category = Category::find($merchant->category_id);
                    $subcategory = Category::find($merchant->subcategory_id);
                    $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                    $mp->identify($identity);
                    $mp->track('Offer Impression', array(
                        '$city' => $geoip->city_name,
                        'OfferId' => $view->offer_id,
                        'OfferName' => $entity->name,
                        'Environment' => App::environment(),
                        'MerchantId' => $entity->merchant_id,
                        'MerchantName' => $merchant->display,
                        'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                        'LocationId' => $location->id,
                        'FranchiseId' => $location->franchise_id,
                        '$region' => $geoip->region_name,
                        'Category' => !empty($category) ? $category->name : '',
                        'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                        'CompanyID' => !empty($company) ? $company->id : '',
                        'CompanyName' => !empty($company) ? $company->name : '',
                        'UserType' => ($view->user_id != 0 ? 'User' : 'Nonmember')
                    ));
                    $job->delete();
                });
            }

            $view->offer = $offer;
            $entity = SOE\DB\Entity::join('merchants', 'entities.merchant_id', '=', 'merchants.id')
                                    ->where('entities.id', '=', $this->id)
                                    ->where('entities.entitiable_type', '=', 'Offer')
                                    ->first(array('entities.*', DB::raw('merchants.about as merchant_about')));
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $view->entity = $ent;
            $view->company_logo = (!empty($company))?$company->logo_image:'';
            $view->is_certified = $franchise->is_certified;
            $view->is_sohi_trial = $franchise->is_sohi_trial;
            if($viewerType == 'User')
            {
                $clip = SOE\DB\UserClipped::where('user_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->where('is_deleted', '=', '0')
                                        ->first();
            }
            else
                $clip = array();

            $view->is_clipped = !empty($clip);
            $column = $viewerType == 'User' ? 'user_id' : 'nonmember_id';
            $prints = SOE\DB\UserPrint::where($column, '=', $viewer->id)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();
            $view->can_print = $prints < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::where('reviewable_id', '=', $offer->id)
                                    ->where('reviewable_type', '=', 'Offer')
                                    ->where('is_deleted', '=', '0')
                                    ->get(array('user_id', 'upvotes'));

            $up_count = 0;
            $down_count = 0;
            $my_review = 0;
            foreach($reviews as $review)
            {
                if($review->upvotes == 1)
                {
                    $up_count++;
                }
                else
                {
                    $down_count++;
                }
                $my_review = $review->$column == $viewer->id ? $review->upvotes : $my_review;
            }
            $about = $location->about ? $location->about : $view->entity->merchant_about;
            $truncated = $this->truncate($about);
            $view->entity->merchant_about_truncated = $truncated;
            $view->entity->merchant_about = $about;
            $view->down_count = $down_count;
            $view->up_count = $up_count;
            $view->my_review = $my_review;
            $view->offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
            return $view;
        }
    }

    /**
     * Share this entity.
     *
     * @param UserRepository    $sharer
     * @param string            $type The type of share to be done, email or facebook
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function share(UserRepository $sharer, $type, $params = array())
    {
        if($this->primary_key)
        {
            switch ($type)
            {
                case 'email':
                    return $this->emailShare($sharer, $params);
                    break;

                case 'facebook':
                    return $this->facebookShare($sharer, $params);
                    break;
            }
        }
    }

    /**
     * Send and record a email share.
     *
     * @param UserRepository    $sharer
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function emailShare(UserRepository $sharer, $params = array())
    {
        $share_object = Share::create(array(
            'user_id' => $sharer->id,
            'shareable_id' => $this->primary_key,
            'shareable_type' => 'Entity',
            'type' => 'email'
        ));
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        if(!isset($params['emails']))
            return 'no emails';
        $emails = $params['emails'];
        $message_text = isset($params['message']) ?  $params['message'] : 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.';
        $from_email = isset($params['from_email']) ? $params['from_email'] : $sharer->email;
        $sharer_name = isset($params['sharer_name']) ? $params['sharer_name'] : $sharer->name;
        $merchant = Merchant::find($this->merchant_id);
        $coupon_expire = date('m-d-Y', strtotime($this->expires_at));
        $data = array(
            'sharer_name' => $sharer_name,
            'message_text' => $message_text,
            'entity' => $this,
            'geoip' => $geoip
        );
        $entity_id = $this->primary_key;
        $emails_to_share_array = explode(",",$emails);
        try
        {
            Mail::send('emails.sharing', $data, function($message) use($emails_to_share_array, $share_object, $from_email, $sharer_name, $entity_id)
            {
                $entity = Entity::find($entity_id);
                $message->to($emails_to_share_array[0]);
                $message->subject($sharer_name.' Shared an '.$entity->entitiable_type.' With You!');
                $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
                for($i=1; $i < count($emails_to_share_array); $i++)
                {
                    $message->bcc($emails_to_share_array[$i]);
                }
            });

            DB::table('share_emails')->insert(array(
                'share_id' => $share_object->id,
                'share_email' => $emails_to_share_array[0],
                'shareable_id' => $entity_id,
                'shareable_type' => 'Entity',
                'created_at' => DB::raw('NOW()'),
                'updated_at' => DB::raw('NOW()')
            ));
            for($i = 1; $i < count($emails_to_share_array); $i++)
            {
                DB::table('share_emails')->insert(array(
                    'share_id' => $share_object->id,
                    'share_email' => trim($emails_to_share_array[$i]),
                    'shareable_id' => $entity_id,
                    'shareable_type' => 'Entity',
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()')
                ));
            }
        }
        catch(Exception $e)
        {
            return 'sending error';
        }
        return $share_object;
    }

    /**
     * Record a facebook share.
     *
     * @param UserRepository    $sharer
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function facebookShare(UserRepository $sharer, $params = array())
    {
        $share_object = Share::create(array(
            'user_id' => $sharer->id,
            'shareable_id' => $this->primary_key,
            'shareable_type' => 'Entity',
            'type' => 'facebook'
        ));

        return $share_object;
        /*$facebook = new Facebook(array(
            'appId' => Config::get('integrations.facebook.app_id'),
            'secret' => Config::get('integrations.facebook.secret')
        ));
        $user = $facebook->getUser();

        if ($user) {
            try {
                $geoip = json_decode(GeoIp::getGeoIp('json'));
                // Proceed knowing you have a logged in user who's authenticated.
                $message = $message == '' ? 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.' : $message;
                $ret_obj = $facebook->api('/me/feed', 'POST',
                                array(
                                  'picture' => $this->path,
                                  'link' => URL::to('/coupons/'.$this->merchant_slug.'/'.$this->location_id.'/'.$geoip->city_name.'/printable/'.$this->primary_key),
                                  'message' => $message,
                                  'name' => $this->name,
                                  'caption' => strip_tags($this->entitiable()->description)
                            ));
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }

        if ($user) {
            $url = $facebook->getLogoutUrl();
            $url = '/';
        } else {
            $statusUrl = $facebook->getLoginStatusUrl();
            $url = $facebook->getLoginUrl(array('scope' => 'publish_actions', 'redirect_uri' => URL::back().'?modal='));
        }

        return $url;*/
    }

    /**
     * Retrieve the entitiable object.
     *
     * @return mixed
     */
    public function entitiable()
    {
        if($this->primary_key)
        {
            $entitiable_type = $this->entitiable_type;
            return $entitiable_type::find($this->entitiable_id);
        }
    }

    /***** API METHODS *****/

    /**
     * Retrieve entities based on category_id, latitude, longitude, type, sort, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of entities.
     */
    public function apiGetByCategory()
    {
        $person = Auth::check() ? Auth::User() : Nonmember::blank();
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $state = Input::get('state', $geoip->region_name);
        $city = Input::get('city', $geoip->city_name);
        $lat = Input::get('latitude', $geoip->latitude);
        $lng = Input::get('longitude', $geoip->longitude);
        $type = Input::get('type', null);
        $category_id = Input::get('category_id');
        $sort = Input::get('sort', 'nearest');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        $radius = Input::get('radius', 0);
        return $this->format($this->getByCategory($person, $city, $state, $lat, $lng, $type, $category_id, $sort, $page, $limit, $radius));
    }

     /**
     * Retrieve featured entities based on user_id, user_type, state, latitude, longitude.
     *
     * @api
     *
     * @return mixed Formatted array of entities.
     */
    public function apiGetFeatured()
    {
        $user_id = Input::get('user_id');
        $user_type = Input::get('user_type');
        $state = Input::get('state');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $type = Input::get('type');
        $radius = Input::get('radius', 0);
        $category = Input::get('category', 'all');
        $subcategory = Input::get('subcategory', '');
        $person = strtolower($user_type) == 'user' ? User::find($user_id) : Nonmember::find($user_id);
        return $this->format($this->getFeatured($person, $type, $state, $latitude, $longitude, $radius, $category, $subcategory));
    }

}

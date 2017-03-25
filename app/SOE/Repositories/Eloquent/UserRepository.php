<?php namespace SOE\Repositories\Eloquent;

class UserRepository extends BaseRepository implements \UserRepositoryInterface, \RankableInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'email',
        'password',
        'username',
        'type',
        'name',
        'address',
        'address2',
        'city',
        'state',
        'homezip',
        'workzip',
        'latitude',
        'longitude',
        'age',
        'birthday',
        'sex',
        'facebookid',
        'accesskey',
        'secretkey',
        'ip',
        'signup_source',
        'reputation',
        'badrep_date',
        'is_suspended',
        'win5kid',
        'rank_food_dining',
        'rank_home_services',
        'rank_health_beauty',
        'rank_auto_transportation',
        'rank_travel_entertainment',
        'rank_retail_fashion',
        'rank_special_services',
        'food_dining_preference',
        'home_services_preference',
        'health_beauty_preference',
        'auto_transportation_preference',
        'travel_entertainment_preference',
        'retail_fashion_preference',
        'special_services_preference',
        'is_deleted',
        'password_reset_notification',
        'contest_end_notification',
        'daily_deal_end_notification',
        'coupon_end_notification',
        'unredeemed_notification',
        'new_offers_notification',
        'love_offers_notification',
        'signup_source_id',
        'rank_community',
        'community_preference',
        'signup_reference',
    );

    protected $model = 'User';
    protected $entityRepository;
    protected $featureRepository;
    protected $zipcodeRepository;

    public function __construct(
        \AssetRepositoryInterface $assets,
        \EntityRepositoryInterface $entityRepository,
        \FeatureRepositoryInterface $featureRepository,
        \VehicleEntityRepositoryInterface $vehicleEntities,
        \ZipcodeRepositoryInterface $zipcodeRepository
    )
    {
        $this->assets = $assets;
        $this->entityRepository = $entityRepository;
        $this->featureRepository = $featureRepository;
        $this->vehicleEntities = $vehicleEntities;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->defaultConn = 'mysql-write';
        parent::__construct();
    }

    /**
     * Retrieve an User by Id, overrides BaseRepository find().
     *
     * @param   int     $user_id
     * @param   boolean $with_trashed Include soft deleted results.
     * @return mixed
     */
    public function find($user_id = null, $with_trashed = false, $write_db = false)
    {
        if($with_trashed)
            $user = $this->query()->withTrashed()->find($user_id);
        else
            $user = $this->query()->find($user_id);
        if(!empty($user))
        {
            return $user;
        }
        return;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query()
    {
        return \SOE\DB\User::on('mysql-write');
    }

    /**
     * Retrieve a User by email address.
     *
     * @param  string  $email
     * @return User
     */
    public function findByEmail($email)
    {
        $user = $this->query()->where('email', '=', $email)->first();
        return $user;
    }

    public function getByFilter($filter, $page = 0, $limit = 0, $type = '')
    {
        $query = $this->query()->where(function($query) use ($filter)
            {
                $query->where('email', 'LIKE', '%'.$filter.'%');
                $query->orWhere('name', 'LIKE', '%'.$filter.'%');
            });
        if($type != '')
        {
            $types = explode(',', $type);
            foreach($types as $t)
            {
                $t = ucwords(strtolower(trim($t)));
                $query->where('type', 'LIKE', '%'.$t.'%');
            }
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query->take($limit)->skip($page*$limit);
        }
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);
        return array_merge($return, $stats);
    }

    /**
     * Retrieve Users by email address.
     *
     * @param  string  $email
     * @return User
     */
    public function searchByEmail($email = '')
    {
        if (strlen($email) >= 3){
            $users = $this->query()->where('email', 'LIKE', $email.'%')->get();
        }
        return $users;
    }

    /**
     * Retrieve a list of users who have not been ranked recently.
     *
     * @param int   $limit Default 50;
     * @return Users
     */
    public function getRankable($limit = 50)
    {
        return $this->query()->take($limit)->orderBy('ranked_at', 'asc')->get();
    }

    public function getLtvReport($start = null, $end = null, $sortBy = 'ltv', $sortDir = 'desc', $page = 0, $limit = 10)
    {
        $dates = "where created_at <= '".($end ? date('Y-m-d 23:59:59', strtotime($end)) : date('Y-m-d 23:59:59'))."'";
        $dates = $start ? $dates." and created_at >= '".date('Y-m-d 00:00:00', strtotime($start))."'" : $dates;
        $base_query = \DB::table('users')
            ->leftJoin(\DB::raw('(select count(*) as prints, user_id, MAX(created_at) as prints_latest from user_prints '.$dates.' group by user_id) up'), 'users.id', '=', 'up.user_id')
            ->leftJoin(\DB::raw('(select count(*) as views, user_id, MAX(created_at) as views_latest from user_views '.$dates.' group by user_id) uv'), 'users.id', '=', 'uv.user_id')
            ->leftJoin(\DB::raw('(select count(*) as sohi_quotes, user_id, MAX(created_at) as sohi_latest from quotes '.$dates.' group by user_id) q'), 'users.id', '=', 'q.user_id')
            ->leftJoin(\DB::raw('(select count(*) as soct_quotes, user_id, MAX(created_at) as soct_latest from auto_quotes '.$dates.' group by user_id) aq'), 'users.id', '=', 'aq.user_id')
            ->leftJoin(\DB::raw('(select count(*) as apps, user_id, MAX(app_latest) as a_latest from (select count(*) as contest_apps, user_id, MAX(created_at) as app_latest from contest_applications '.$dates.' group by user_id,contest_id) ca group by ca.user_id) a'), 'users.id', '=', 'a.user_id')
            ->where('users.type', 'Member');
        $stats_query = clone($base_query);

        $users = $base_query->skip($page*$limit)
                            ->take($limit)
                            ->orderBy($sortBy, $sortDir)
                            ->get(array(
                                    'users.id', 
                                    'users.email', 
                                    'users.type', 
                                    'up.prints', 
                                    \DB::raw('uv.views as views'), 
                                    'q.sohi_quotes', 
                                    'aq.soct_quotes', 
                                    'a.apps',
                                    \DB::raw('((IF(up.prints IS NULL, 0, up.prints*3))
                                        +(IF(uv.views IS NULL, 0, uv.views*0.05))
                                        +(IF(q.sohi_quotes IS NULL, 0, q.sohi_quotes*15))
                                        +(IF(aq.soct_quotes IS NULL, 0, aq.soct_quotes*15))
                                        +(IF(a.apps IS NULL, 0, a.apps*2))) as ltv'),
                                    \DB::raw('GREATEST(IF(prints_latest IS NULL, 0, prints_latest), 
                                        IF(views_latest IS NULL, 0, views_latest), 
                                        IF(sohi_latest IS NULL, 0, sohi_latest), 
                                        IF(soct_latest IS NULL, 0, soct_latest),
                                        IF(a_latest IS NULL, 0, a_latest)) as most_recent_activity')
                                    ));
        $stats = $stats_query->get(array(
                                    \DB::raw('COUNT(*) as total_members'),
                                    \DB::raw('AVG(IFNULL(up.prints, 0)) as avg_prints'),
                                    \DB::raw('AVG(IFNULL(uv.views, 0)) as avg_views'), 
                                    \DB::raw('AVG(IFNULL(q.sohi_quotes, 0)) as avg_sohi_quotes'), 
                                    \DB::raw('AVG(IFNULL(aq.soct_quotes, 0)) as avg_soct_quotes'), 
                                    \DB::raw('AVG(IFNULL(a.apps, 0)) as avg_apps'),
                                    \DB::raw('AVG((IF(up.prints IS NULL, 0, up.prints*3))
                                        +(IF(uv.views IS NULL, 0, uv.views*0.05))
                                        +(IF(q.sohi_quotes IS NULL, 0, q.sohi_quotes*15))
                                        +(IF(aq.soct_quotes IS NULL, 0, aq.soct_quotes*15))
                                        +(IF(a.apps IS NULL, 0, a.apps*2))) as avg_ltv')
                                    ));

        return array('members' => $users, 'stats' => $stats);
    }

    /**
     * Get an array of recommended Entities.
     * See getRecommendations in EntityRepository
     * @return mixed Entities
     */
    public function getRecommendations($user, $limit = 0, $geoip = array(), $ordering = 'rand', $type = 'soe', $stuffing = null, $entity_type = null, $for_newsletter = false, $exclusions = array())
    {
        $show_demo = $for_newsletter ? false : $this->showDemo($user);
        if($type == 'soe')
        {
            $rankings = $this->getRankings($user);
            $preferences = $this->getPreferences($user);
            $recommendations = $this->entityRepository->getRecommendations($rankings, $preferences, $show_demo, $geoip, $limit, $ordering, $stuffing, $entity_type, $exclusions);
        }
        else if($type == 'sohi')
        {
            $recommendations = $this->entityRepository->getSohiRecommendations($show_demo, $geoip, $limit, $ordering);
        }
        else if($type == 'soct')
        {
            $recommendations = $this->entityRepository->getSoctRecommendations($show_demo, $geoip, $limit, $ordering);
        }
        $this->checkZipcode($user);
        
        if(!$for_newsletter)
            $recommendations['objects'] = $this->markClipped($user, $recommendations['objects']);

        return $recommendations;
    }

    /**
     * Determine if the given type string is contained in the given user's type field.
     *
     * @param SOE\DB\User $user
     * @param string $type
     * @return boolean
     */
    public function checkType(\SOE\DB\User $user, $type_string)
    {
        $types = explode(',', $user->type);
        $found = false;
        foreach($types as $type)
        {
            if(strtolower(trim($type)) == strtolower($type_string))
            {
                $found = true;
            }
        }
        return $found;
    }

    public function addType(\SOE\DB\User $user, $type_string)
    {
        if(!stristr($user->type, $type_string))
        {
            $user->type = $user->type.','.$type_string;
            $user->save();
        }
        return $user;
    }

    public function getFranchise($user_id, $type = null)
    {
        $query = \SOE\DB\Franchise::where('franchise_users.user_id', $user_id);
        if($type)
            $query->where('franchise_users.type', $type);
        $query->join('franchise_users', 'franchise_users.franchise_id', '=', 'franchises.id')
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id');
        return $query->first(array('franchises.*', 'merchants.display'));
    }

    public function setFranchise($user_id, $franchise_id, $type = null)
    {
        $franchise = \SOE\DB\Franchise::find($franchise_id);
        if(!$franchise)
            return false;

        $merchant = \SOE\DB\Merchant::find($franchise->merchant_id);
        $franchise->name = $franchise->name == '' ? $merchant->display : $franchise->name;

        $association = new \SOE\DB\FranchiseUser;
        $association->user_id = $user_id;
        $association->franchise_id = $franchise_id;
        $association->type = $type ? $type : '';
        $association->save();

        return $franchise;
    }

    public function removeFranchise($user_id, $franchise_id, $type = null)
    {
        $query = \SOE\DB\FranchiseUser::where('user_id', $user_id)
            ->where('franchise_id', $franchise_id);
        if($type)
            $query->where('type', $type);

        return $query->delete();
    }

    /**
     * Determine whether or not this user should be able to see demo objects.
     *
     * @param SOE\DB\User $user
     * @return boolean
     */
    public function showDemo(\SOE\DB\User $user)
    {
        $types = explode(',', $user->type);
        return (in_array('Employee', $types) || in_array('Demo', $types));
    }

    /**
     * Return the category rankings for this Person.
     *
     * @param SOE\DB\User $user
     * @return array
     */
    public function getRankings(\SOE\DB\User $user)
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aRankings = array();
        foreach($cat_slugs as $slug)
        {
            $aRankings[$slug] = $user->{'rank_'.$slug};
            $aPreferences = $user->{$slug.'_preference'};
            //$aRankings[$slug] = $aRankings[$slug] * $aPreferences;
            //echo $aRankings[$slug].' ';
        }
        return $aRankings;
    }

    /**
     * Return the category preferences for this Person.
     *
     * @param SOE\DB\User $user
     * @return array
     */
    public function getPreferences(\SOE\DB\User $user)
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services', 'community');
        $aPreferences = array();
        foreach($cat_slugs as $slug)
        {
            $aPreferences[$slug] = $user->{$slug.'_preference'};
        }
        return $aPreferences;
    }

    public function updatePreferences($user_id, $params)
    {
        $user = $this->find($user_id);
        if(!$user)
            return false;
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services', 'community');
        foreach($cat_slugs as $slug)
        {
            if(isset($params[$slug.'_preference']))
                $user->{$slug.'_preference'} = $params[$slug.'_preference'];
        }
        $user->save();
        return $user;
    }

    /**
     * Determine whether this recovery link is still valid and delete the record after validating.
     *
     * @param string    $email The email that this recovery was sent to.
     * @param string    $uniq The unique id identifying this recovery.
     * @param string    $type The type of recovery.
     * @return boolean
     */
    public function validateRecovery($email, $uniq, $type)
    {
        $valid = \DB::connection('mysql-write')->table('recovery')->where('email', '=', $email)
                                    ->where('recovery_id', '=', $uniq)
                                    ->where('type', '=', $type)
                                    ->where('expires_at', '>', \DB::raw("NOW()"))
                                    ->first();
        if(!empty($valid))
        {
            \DB::connection('mysql-write')->table('recovery')->where('email', '=', $email)
                                ->where('recovery_id', '=', $uniq)
                                ->where('type', '=', $type)
                                ->delete();
        }

        return !empty($valid);
    }

    public function getRecovery($email, $uniq, $type)
    {
        return \DB::connection('mysql-write')->table('recovery')->where('email', '=', $email)
                                    ->where('recovery_id', '=', $uniq)
                                    ->where('type', '=', $type)
                                    ->first();
    }

    /**
     * Add an email verification recovery record.
     *
     * @param string    $email The email address this recovery is sent to.
     * @return array    Unique id and expiration timer.
     */
    public function setVerificationRecovery($email)
    {
        $timer = $this->featureRepository->findByName('email_verification_timer');
        $timer = empty($timer) ? 60 : $timer->value;
        return array('uniq' => $this->setRecovery($email, $timer, 'verification'), 'timer' => $timer);
    }

    /**
     * Add a password recovery record.
     *
     * @param string    $email The email address this recovery is sent to.
     * @return array    Unique id and expiration timer.
     */
    public function setPasswordRecovery($email)
    {
        $user = $this->findByEmail($email);
        if(empty($user))
            return;
        $timer = $this->featureRepository->findByName('password_recovery_timer');
        $timer = empty($timer) ? 60 : $timer->value;
        return array('uniq' => $this->setRecovery($email, $timer, 'password'), 'timer' => $timer);
    }

    public function setSignupRecovery($email, $association_id = 0, $association_type = '')
    {
        $timer = $this->featureRepository->findByName('signup_verification_timer');
        $timer = empty($timer) ? 60*24 : $timer->value;
        return array('uniq' => $this->setRecovery($email, $timer, 'signup', $association_id, $association_type), 'timer' => $timer);
    }

    /**
     * Add a record to the recovery table.
     *
     * @param string    $email The email address this recovery is sent to.
     * @param string    $timer The number of minutes before a recovery expires.
     * @param string    $type The type of recovery record.
     * @return void
     */
    protected function setRecovery($email, $timer, $type, $association_id = 0, $association_type = '')
    {
        $uniq = uniqid();
        \DB::table('recovery')->insert(array(
            'created_at' => \DB::raw('NOW()'),
            'updated_at' => \DB::raw('NOW()'),
            'email' => $email,
            'recovery_id' => $uniq,
            'type' => $type,
            'expires_at' => \DB::raw('DATE_ADD(NOW(), INTERVAL '.$timer.' MINUTE)'),
            'association_id' => $association_id,
            'association_type' => $association_type
        ));
        return $uniq;
    }

    /**
     * Mark which entities in a given set of entities have been clipped by the given user.
     *
     * @param SOE\DB\User   $user
     * @param array         $entities
     * @param array         $entities
     */
    public function markClipped(\SOE\DB\User $user, $entities)
    {
        $aEntIDs = array(0);
        $aFavs = array();
        $favorites = $this->getFavorites($user->id);
        foreach($favorites['objects'] as $favorite)
        {
            $aFavs[] = $favorite->favoritable_type.'|'.$favorite->favoritable_id;
        }
        foreach($entities as $entity)
        {
            if(isset($entity->entitiable_type) && $entity->entitiable_type == 'Offer')
            {
                $aEntIDs[] = $entity->entitiable_id;
            }
        }
        $aClipIDs = array(0);
        $clips = \SOE\DB\UserClipped::on('mysql-write')
                                    ->whereIn('offer_id', $aEntIDs)
                                    ->where('user_id', '=', $user->id)
                                    ->where('is_deleted', '=', '0')
                                    ->get(array('offer_id'));
        foreach($clips as $clip)
        {
            $aClipIDs[] = $clip->offer_id;
        }
        foreach($entities as &$ent)
        {
            if(isset($entity->entitiable_type))
                $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
            else if(isset($entity->object_type))
                $ent->is_clipped = in_array('SOE\\DB\\'.$ent->object_type.'|'.$ent->id, $aFavs) ? 1 : 0;

        }

        return $entities;
    }

    public function getByAssignmentType(\SOE\DB\AssignmentType $type)
    {
        return \SOE\DB\User::join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')
                            ->where('user_assignment_types.assignment_type_id', '=', $type->id)
                            ->orderBy('name', 'asc')
                            ->get(array('users.*'));
    }

    /**
     * Get the count of non-employee members.
     *
     * @return int
     */
    public function getMemberCount($start = null, $end = null, $market = null)
    {
        $query =  $this->query()->where('type', 'NOT LIKE', '%Employee%');
        if($start)
            $query->where('created_at', '>=', $start);
        if($end)
            $query->where('created_at', '<=', $end);
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);

            if($abbr)
            {
                $query->where('state', $abbr);
            }
        }
        return $query->remember(60*12)->count();
    }

    /**
     * Get the count of the given type of user favorites.
     *
     * @param string    $type The type of user favorites to count.
     * @param date  $start
     * @param date  $end
     * @return int
     */
    public function getFavoritesCount($type = null, $start = null, $end = null)
    {
        $favorites = \SOE\DB\UserFavorite::query();
        if($type)
        {
            $favorites = $favorites->where('type', '=', $type);
        }
        if($start)
        {
            $favorites = $favorites->where('created_at', '>=', $start);
        }
        if($end)
        {
            $favorites = $favorites->where('created_at', '<=', $end);
        }
        return $favorites->remember(60*12)->count();
    }

    /**
     * Get the count of the given type of user shares.
     *
     * @param string    $type The type of user shares to count.
     * @param date  $start
     * @param date  $end
     * @return int
     */
    public function getSharesCount($type = null, $start = null, $end = null)
    {
        $shares = \SOE\DB\Share::query();
        if($type)
        {
            $shares = $shares->where('type', '=', $type);
        }
        if($start)
        {
            $shares = $shares->where('created_at', '>=', $start);
        }
        if($end)
        {
            $shares = $shares->where('created_at', '<=', $end);
        }
        return $shares->remember(60*12)->count();
    }

    /**
     *
     *
     *
     */
    public function getEmployees($page = 0, $limit = 0)
    {
        $query = \SOE\DB\User::where('type', 'LIKE', '%Employee%');
        if($limit)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        return $query->get();
    }

    public function getSalesReps($page = 0, $limit = 0)
    {
        $query = $this->query()
            ->join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')
            ->where('assignment_type_id', '=', 1) // Sales Person
            ->groupBy('users.id')
            ->orderBy('users.name');
        $stats = $this->getStats(clone $query, $limit, $page, true);
        if($limit)
            $query->take($limit)->skip($page*$limit);
        $results = $query->get(array('users.*'));
        $stats['stats']['returned'] = count($results);
        $return = array('objects' => $results);
        return array_merge($return, $stats);
    }

    /**
     * Check to see if the user's zipcode is set, if not find the nearest zipcode.
     *
     * @param SOE\DB\User $user
     * @return void
     */
    protected function checkZipcode(\SOE\DB\User $user)
    {
        if($user->zipcode == '' || $user->zipcode == 'Your ZIP/P')
        {
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            $zipcode = $this->zipcodeRepository->getClosest($geoip->latitude, $geoip->longitude);
            $user->zipcode = empty($zipcode) ? '' : $zipcode->zipcode;
            $user->save();
        }
    }

    public function getFavorites($user_id, $favoritable_type = null, $favoritable_id = null, $page = 0, $limit = 0)
    {
        if(!is_array($favoritable_type))
            $favoritable_type = explode(',', $favoritable_type);
        $query = $this->find($user_id)
                    ->favorites()
                   ->where('is_deleted', '=', 0);
        if($favoritable_type)
        {
            $include_ve = false;
            foreach($favoritable_type as $type)
            {
                if(stristr($type, 'VehicleEntity'))
                    $include_ve = true;
            }
            $query = $query->where(function($query) use ($include_ve, $favoritable_type)
            {
                $query->whereIn('user_favorites.favoritable_type', $favoritable_type);
                if($include_ve)
                    $query->orWhere('user_favorites.favoritable_type', 'LIKE', 'VehicleEntity%');
            });
        }
        if($favoritable_id)
            $query = $query->where('user_favorites.favoritable_id', $favoritable_id);
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query = $query->skip($page*$limit)->take($limit);
        $objects = $query->get(array('user_favorites.*'));
        $return = array('objects' => $objects);
        $stats['stats']['returned'] = count($objects);
        return array_merge($return, $stats);
    }

    public function favorites($user_id, $favoritable_type = null, $favoritable_id = null, $page = 0, $limit = 0)
    {
        $favorites = $this->getFavorites($user_id, $favoritable_type, $favoritable_id, $page, $limit);
        foreach($favorites['objects'] as $key => &$favorite)
        {
            if(stristr($favorite->favoritable_type, 'VehicleEntity'))
            {
                $pieces = explode('-', $favorite->favoritable_type);
                $favoritable = count($pieces) == 2 ? $this->vehicleEntities->query()->where('vendor_inventory_id', $favorite->favoritable_id)->where('vendor', $pieces[1])->first() : null;
                if(!$favoritable)
                {
                    unset($favorites['objects'][$key]);
                    $favorites['stats']['returned']--;
                    $favorites['stats']['total']--;
                    $favorite->is_deleted = 1;
                    $favorite->save();
                    continue;
                }
                $favoritable->is_saved = 1;
                $favorite->favoritable = $favoritable->toArray();
            }
            else
            {
                $favoritable = $favorite->favoritable;
                $favorite->favoritable = $favorite->favoritable;
            }
            switch ($favorite->favoritable_type)
            {
                case 'VehicleStyle':
                    $favorite = $this->addNewCarRelationships($favorite, $favoritable);
                    break;
                case 'UsedVehicle':
                    $favorite = $this->addUsedCarRelationships($favorite, $favoritable);
                    break;
                case 'Location':
                    $favorite = $this->addLocationRelationships($favorite, $favoritable);
                    break;
            }
        }
        return $favorites;
    }

    public function view(\PersonInterface $viewer, \ViewableInterface $viewable)
    {
        return $viewable->view($viewer);
    }

    public function getActiveReport($days = 30)
    {
        $auto_quotes = \DB::table('users')->select('users.id')->join('auto_quotes', 'users.id', '=', 'auto_quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('auto_quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $apps = \DB::table('users')->select('users.id')->join('contest_applications', 'users.id', '=', 'contest_applications.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('contest_applications.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $quotes = \DB::table('users')->select('users.id')->join('quotes', 'users.id', '=', 'quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $reviews = \DB::table('users')->select('users.id')->join('reviews', 'users.id', '=', 'reviews.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('reviews.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $shares = \DB::table('users')->select('users.id')->join('shares', 'users.id', '=', 'shares.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('shares.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $suggestions = \DB::table('users')->select('users.id')->join('suggestions', 'users.id', '=', 'suggestions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('suggestions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_clipped = \DB::table('users')->select('users.id')->join('user_clipped', 'users.id', '=', 'user_clipped.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_clipped.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_favorites = \DB::table('users')->select('users.id')->join('user_favorites', 'users.id', '=', 'user_favorites.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_favorites.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_impressions = \DB::table('users')->select('users.id')->join('user_impressions', 'users.id', '=', 'user_impressions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_impressions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_prints = \DB::table('users')->select('users.id')->join('user_prints', 'users.id', '=', 'user_prints.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_prints.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_searches = \DB::table('users')->select('users.id')->join('user_searches', 'users.id', '=', 'user_searches.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_searches.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_views = \DB::table('users')->select('users.id')->join('user_views', 'users.id', '=', 'user_views.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_views.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));

        $total_active = $user_views->union($user_searches)
                            ->union($user_prints)
                            ->union($user_impressions)
                            ->union($user_favorites)
                            ->union($user_clipped)
                            ->union($suggestions)
                            ->union($shares)
                            ->union($reviews)
                            ->union($quotes)
                            ->union($apps)
                            ->union($auto_quotes)
                            ->get();
                            
        $total_active = count($total_active);

        $auto_quotes = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'AutoQuotes' as activity_type")))->join('auto_quotes', 'users.id', '=', 'auto_quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('auto_quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $apps = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'ContestApplications' as activity_type")))->join('contest_applications', 'users.id', '=', 'contest_applications.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('contest_applications.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $quotes = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'SohiQuotes' as activity_type")))->join('quotes', 'users.id', '=', 'quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $reviews = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'Reviews' as activity_type")))->join('reviews', 'users.id', '=', 'reviews.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('reviews.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $shares = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'Shares' as activity_type")))->join('shares', 'users.id', '=', 'shares.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('shares.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $suggestions = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'Suggestions' as activity_type")))->join('suggestions', 'users.id', '=', 'suggestions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('suggestions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_clipped = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserClipped' as activity_type")))->join('user_clipped', 'users.id', '=', 'user_clipped.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_clipped.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_favorites = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserFavorites' as activity_type")))->join('user_favorites', 'users.id', '=', 'user_favorites.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_favorites.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_impressions = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserImpressions' as activity_type")))->join('user_impressions', 'users.id', '=', 'user_impressions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_impressions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_prints = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserPrints' as activity_type")))->join('user_prints', 'users.id', '=', 'user_prints.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_prints.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_searches = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserSearches' as activity_type")))->join('user_searches', 'users.id', '=', 'user_searches.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_searches.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
        $user_views = \DB::table('users')->select(array(\DB::raw('COUNT(DISTINCT(users.id)) as total'), \DB::raw("'UserViews' as activity_type")))->join('user_views', 'users.id', '=', 'user_views.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_views.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));

        $type_totals = $user_views->unionAll($user_searches)
                            ->unionAll($user_prints)
                            ->unionAll($user_impressions)
                            ->unionAll($user_favorites)
                            ->unionAll($user_clipped)
                            ->unionAll($suggestions)
                            ->unionAll($shares)
                            ->unionAll($reviews)
                            ->unionAll($quotes)
                            ->unionAll($apps)
                            ->unionAll($auto_quotes)
                            ->get();

        $aTypes = array();
        foreach($type_totals as $total)
        {
            $aTypes[$total->activity_type] = $total->total;
        }
        return array('total' => $total_active, 'types' => $aTypes);

        /*$members = \DB::table('users')->whereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('auto_quotes', 'users.id', '=', 'auto_quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('auto_quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('contest_applications', 'users.id', '=', 'contest_applications.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('contest_applications.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('quotes', 'users.id', '=', 'quotes.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('quotes.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('reviews', 'users.id', '=', 'reviews.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('reviews.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('shares', 'users.id', '=', 'shares.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('shares.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('suggestions', 'users.id', '=', 'suggestions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('suggestions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_clipped', 'users.id', '=', 'user_clipped.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_clipped.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_favorites', 'users.id', '=', 'user_favorites.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_favorites.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_impressions', 'users.id', '=', 'user_impressions.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_impressions.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_prints', 'users.id', '=', 'user_prints.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_prints.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_searches', 'users.id', '=', 'user_searches.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_searches.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orWhereIn('users.id', array_merge(array(0), \DB::table('users')->select('users.*')->join('user_views', 'users.id', '=', 'user_views.user_id')->where('users.type', 'NOT LIKE', '%Employee%')->where('user_views.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'))->lists('users.id')))
                                    ->orderBy('users.created_at', 'desc')
                                    ->get();
        return array('objects' => $members);*/
    }

    /**
     * Check to see if the given user has reviewed the given object.
     *
     * @param int $id The id of the reviewable object.
     * @param string $type The type of the reviewable object.
     * @param object $user
     * @return boolean
     */
    public function hasReviewed($id, $type, $user)
    {
        $review = \SOE\DB\Review::on('mysql-write')
                                ->where('user_id', $user->id)
                                ->where('reviewable_id', $id)
                                ->where('reviewable_type', $type)
                                ->where('is_deleted', '0')
                                ->first();
        return $review ? true : false;
    }

    /**
     * Check to see if the given user has favorited the given object.
     *
     * @param int $id The id of the favoritable object.
     * @param string $type The type of the favoritable object.
     * @param object $user
     * @return boolean
     */
    public function hasFavorited($id, $type, $user)
    {
        $favorite = \SOE\DB\UserFavorite::on('mysql-write')
                                ->where('user_id', $user->id)
                                ->where('favoritable_id', $id)
                                ->where('favoritable_type', $type)
                                ->where('is_deleted', '0')
                                ->first();
        return $favorite ? true : false;
    }

    protected function addNewCarRelationships($favorite, $favoritable)
    {
        $favorite->favoritable->incentives = $favoritable->incentives;
        $favorite->favoritable->assets = $favoritable->assets;
        $favorite->favoritable->display_image = $favoritable->displayImage;
        return $favorite;
    }

    protected function addUsedCarRelationships($favorite, $favoritable)
    {
        $favorite->favoritable->merchant = $favoritable->merchant;
        $favorite->favoritable->merchant->locations = $favorite->favoritable->locations;
        return $favorite;
    }

    protected function addLocationRelationships($favorite, $favoritable)
    {
        $favorite->favoritable->merchant = $favoritable->merchant;
        $logo = $this->assets->getLogo($favoritable->merchant);
        $favorite->logo = $logo ? $logo->path : '';
        $favorite->favoritable->merchant->category = $favoritable->merchant->category;
        $favorite->favoritable->merchant->subcategory = $favoritable->merchant->subcategory;
        return $favorite;
    }
}

/**
 * Handle the User creation event.
 *
 * @param SOE\DB\User $user
 * @return void
 */
\SOE\DB\User::created(function($user)
{
    $user->win5kid = \PseudoCrypt::hash($user->id, 6);
    $user->save();
    \Event::fire('user.created', array($user));
    /*$data = array(
        'email' => $user->email,
        'name' => $user->name
    );
    \Mail::queueOn('SOE_Tasks', 'emails.validate', $data, function($message) use ($data)
    {
        $message->to($data['email'], $data['name'])->subject('Welcome Aboard');
    });*/
    $userRepository = \App::make('UserRepositoryInterface');
    $email = $user->email;
    $recovery = $userRepository->setVerificationRecovery($email);
    $data = array(
        'key' => $recovery['uniq'],
        'email' => $email,
        'timer' => $recovery['timer']
    );
    \Mail::queueOn('SOE_Tasks', 'emails.emailverify', $data, function($message) use ($email)
    {
        $message->to($email)->subject('Verify Your Email');
        $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
    });

    //Add user to Infusion Soft
    $appEmail = \App::make('AppEmailInterface');
    $appEmail->addUser($user);
});

/**
 * Handle the User updated event.
 *
 * @param SOE\DB\User $user
 * @return void
 */
\SOE\DB\User::updated(function($user)
{
    $appEmail = \App::make('AppEmailInterface');
    if(!empty($user))
        $appEmail->updateUser($user);
});




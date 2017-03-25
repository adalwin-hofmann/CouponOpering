<?php namespace SOE\Repositories\Eloquent;

class ContestRepository extends BaseRepository implements \ContestRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'type',
        'path',
        'starts_at',
        'expires_at',
        'require_user_id',
        'banner',
        'logo',
        'landing',
        'contest_logo',
        'about_us',
        'contest_description',
        'slug',
        'customerio_non_member_attr',
        'customerio_member_attr',
        'views',
        'logo_link',
        'display_name',
        'wufoo_link',
        'contest_rules',
        'merchant_id',
        'markets',
        'keyword_id',
        'fields',
        'tracking_code',
        'company_id',
        'location_id',
        'is_demo',
        'is_active',
        'is_featured',
        'deleted_at',
        'franchise_id',
        'latitude',
        'longitude',
        'radius',
        'is_national',
        'stuffing_priority',
        'winning_number_min',
        'winning_number_max',
        'winning_number_type',
        'winning_number_length',
        'follow_up_id',
        'follow_up_type',
        'follow_up_text',
        'follow_up_sent_at',
        'is_location_specific',
        'is_automated',
        'is_location_independent',
        'current_inventory',
        'total_inventory',
        'date_ended'
    );

    protected $model = 'Contest';
    protected $userRepository;
    protected $zipcodeRepository;

    public function __construct(
        \CategoryRepositoryInterface $categories,
        \ContestLocationRepositoryInterface $contestLocations,
        \EntityRepositoryInterface $entities,
        \FranchiseRepositoryInterface $franchises,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants,
        \OfferRepositoryInterface $offers,
        \UserRepositoryInterface $userRepository,
        \ZipcodeRepositoryInterface $zipcodeRepository
    )
    {
        $this->categories = $categories;
        $this->contestLocations = $contestLocations;
        $this->entities = $entities;
        $this->franchises = $franchises;
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->offers = $offers;
        $this->userRepository = $userRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        parent::__construct();
    }

    /**
     * Create the contest.
     *
     * @param array $input
     * @return mixed
     */
    public function create(array $input = array())
    {
        $contest = parent::create($input);
        $this->checkFollowUp($contest);
        $this->generateEntities($contest, isset($input['locations']) ? $input['locations'] : array());
        return $contest;
    }

    public function update($id, array $input = array())
    {
        $contest = parent::update($id, $input);
        $this->checkFollowUp($contest);
        if(!$contest->is_location_independent)
            $this->contestLocations->removeForContest($contest);
        $this->generateEntities($contest, isset($input['locations']) ? $input['locations'] : array());
        return $contest;
    }

    /**
     * Check if contest has a follow up, and make sure the follow up is marked.
     *
     * @param object $contest
     * @return void
     */
    public function checkFollowUp($contest)
    {
        if($contest->follow_up_id != 0 && $contest->follow_up_type == 'SOE\DB\Offer')
        {
            $this->offers->query()
                ->where('id', $contest->follow_up_id)
                ->update(array('is_followup_for' => $contest->id));
        }
        else
        {
            $this->offers->query()
                ->where('is_followup_for', $contest->id)
                ->update(array('is_followup_for' => 0));
        }
    }

    /**
     * Create or modify entities for the given contest.
     *
     * @param object $contest
     * @param array $locations
     * @return void
     */
    public function generateEntities($contest, array $locations = array())
    {
        if($contest->franchise_id == 0)
        {
            // Delete any entities associated with a merchant
            $this->entities->query()
                ->where('entitiable_type', 'Contest')
                ->where('entitiable_id', $contest->id)
                ->where('merchant_id', '!=', 0)
                ->delete();

            $existing = $this->entities->query()
            ->where('entitiable_type', 'Contest')
            ->where('entitiable_id', $contest->id)
            ->get();
            $zip = $this->zipcodeRepository->getClosest($contest->latitude, $contest->longitude);

            if($existing->count() == 0)
            {
                // Create a new entity for this contest
                $this->entities->create(array(
                    'entitiable_id' => $contest->id,
                    'entitiable_type' => 'Contest',
                    'name' => $contest->name,
                    'slug' => $contest->slug,
                    'location_id' => $contest->id,
                    'category_id' => 0,
                    'subcategory_id' => 0,
                    'latitude' => $contest->latitude,
                    'longitude' => $contest->longitude,
                    'path' => $contest->path,
                    'is_dailydeal' => 0,
                    'rating' => 0,
                    'special_price' => 0,
                    'regular_price' => 0,
                    'is_demo' => $contest->is_demo,
                    'is_active' => $contest->is_active,
                    'starts_at' => $contest->starts_at,
                    'expires_at' => $contest->expires_at,
                    'rating_count' => 0,
                    'savings' => 0,
                    'url' => $contest->logo_link,
                    'print_override' => '',
                    'secondary_type' => $contest->type,
                    'latm' => ($contest->latitude*111133),
                    'lngm' => (111133*cos(deg2rad($contest->latitude))*$contest->longitude),
                    'merchant_id' => 0,
                    'merchant_slug' => '',
                    'merchant_name' => '',
                    'popularity' => 0,
                    'is_featured' => $contest->is_featured,
                    'state' => $zip->state,
                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                    'location_active' => 1,
                    'franchise_active' => 1,
                    'franchise_demo' => 0,
                    'category_slug' => '',
                    'subcategory_slug' => '',
                    'company_id' => 0,
                    'company_name' => '',
                    'is_national' => $contest->is_national
                ));
            }
            else
            {
                // Just updating
                $this->entities->query()
                    ->where('entitiable_id', '=', $contest->id)
                    ->where('entitiable_type', '=', 'Contest')
                    ->update(array(
                        'name' => $contest->name,
                        'slug' => $contest->slug,
                        'category_id' => 0,
                        'subcategory_id' => 0,
                        'latitude' => $contest->latitude,
                        'longitude' => $contest->longitude,
                        'path' => $contest->path,
                        'is_demo' => $contest->is_demo,
                        'is_active' => $contest->is_active,
                        'starts_at' => $contest->starts_at,
                        'expires_at' => $contest->expires_at,
                        'url' => $contest->logo_link,
                        'secondary_type' => $contest->type,
                        'latm' => ($contest->latitude*111133),
                        'lngm' => (111133*cos(deg2rad($contest->latitude))*$contest->longitude),
                        'merchant_id' => 0,
                        'merchant_slug' => '',
                        'merchant_name' => '',
                        'is_featured' => $contest->is_featured,
                        'state' => $zip->state,
                        'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                        'starts_year' => date('Y', strtotime($contest->starts_at)),
                        'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                        'expires_year' => date('Y', strtotime($contest->expires_at)),
                        'location_active' => 1,
                        'franchise_active' => 1,
                        'franchise_demo' => 0,
                        'category_slug' => '',
                        'subcategory_slug' => '',
                        'company_id' => 0,
                        'company_name' => '',
                        'is_national' => $contest->is_national
                    ));
            }
        }
        else
        {
            $merchant = $this->merchants->find($contest->merchant_id);
            $franchise = $this->franchises->find($contest->franchise_id);
            $category = $this->categories->find($merchant->category_id);
            $subcategory = $this->categories->find($merchant->subcategory_id);
            $aLocs = array(0);
            $existing = $this->entities->query()
                ->where('entitiable_id', '=', $contest->id)
                ->where('entitiable_type', '=', 'Contest')
                ->where('entitiable_id', '!=', \DB::raw('location_id'))
                ->first();
            
            if(!empty($existing))
            {
                $location = $this->locations->query()
                    ->where('id', '=', $existing->location_id)
                    ->first();
                if(!empty($location) && $location->franchise_id != $franchise->id)
                {
                    // Franchise has changed, remove old entities.
                    $this->entities->query()
                        ->where('entitiable_id', '=', $contest->id)
                        ->where('entitiable_type', '=', 'Contest')
                        ->delete();
                }
                else if(!empty($location) && $location->franchise_id == $franchise->id)
                {
                    // Franchise has not changed, grab existing entities.
                    $entities = $this->entities->query()
                        ->where('entitiable_type', 'Contest')
                        ->where('entitiable_id', $contest->id)
                        ->get();
                    foreach($entities as $entity)
                    {
                        $aLocs[] = $entity->location_id;
                    }
                }
            }

            if($contest->is_location_specific)
            {
                $newLocs = array_diff($locations, $aLocs);
                $removeLocs = array_diff($aLocs, $locations);
                $removeLocs[] = $contest->id;
                // Delete entities no longer tied to a selected location
                $this->entities->query()
                    ->where('entitiable_type', 'Contest')
                    ->where('entitiable_id', $contest->id)
                    ->whereIn('location_id', $removeLocs)
                    ->delete();

                // Create entities for newly added locations
                foreach($newLocs as $new)
                {
                    $location = $this->locations->find($new);
                    if(!$location)
                        continue;
                    $entity = $this->entities->create(array(
                        'entitiable_id' => $contest->id,
                        'entitiable_type' => 'Contest',
                        'name' => $contest->name,
                        'slug' => $contest->slug,
                        'location_id' => $location->id,
                        'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                        'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'path' => $contest->path,
                        'is_dailydeal' => 0,
                        'rating' => 0,
                        'special_price' => 0,
                        'regular_price' => 0,
                        'is_demo' => $contest->is_demo,
                        'is_active' => $contest->is_active,
                        'starts_at' => $contest->starts_at,
                        'expires_at' => $contest->expires_at,
                        'rating_count' => 0,
                        'savings' => 0,
                        'url' => $contest->logo_link,
                        'print_override' => '',
                        'secondary_type' => $contest->type,
                        'latm' => ($location->latitude*111133),
                        'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                        'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                        'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                        'merchant_name' => empty($merchant) ? '' : $merchant->display,
                        'popularity' => 0,
                        'is_featured' => $contest->is_featured,
                        'state' => $location->state,
                        'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                        'starts_year' => date('Y', strtotime($contest->starts_at)),
                        'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                        'expires_year' => date('Y', strtotime($contest->expires_at)),
                        'location_active' => $location->is_active,
                        'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                        'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                        'category_slug' => empty($category) ? '' : $category->slug,
                        'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                        'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                        'company_name' => empty($company) ? 0 : $company->name,
                        'is_national' => $contest->is_national
                    ));
                }
            }
            else
            {
                if($contest->is_location_independent)
                    $this->createIndependentLocationEntities($contest, $merchant, $franchise, $category, $subcategory);
                else
                    $this->createLocationEntities($contest, $merchant, $franchise, $category, $subcategory);
            }

            // Update existing entities
            $this->entities->query()
                ->where('entitiable_type', 'Contest')
                ->where('entitiable_id', $contest->id)
                ->update(array(
                    'name' => $contest->name,
                    'slug' => $contest->slug,
                    'path' => $contest->path,
                    'is_demo' => $contest->is_demo,
                    'is_active' => $contest->is_active,
                    'starts_at' => $contest->starts_at,
                    'expires_at' => $contest->expires_at,
                    'url' => $contest->logo_link,
                    'secondary_type' => $contest->type,
                    'is_featured' => $contest->is_featured,
                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                    'is_national' => $contest->is_national
                ));
        }
    }

    /**
     * Ensure entities exist for all active franchise locations.
     *
     * @param \SOE\DB\Contest $contest
     * @param \SOE\DB\Merchant $merchant
     * @param \SOE\DB\Franchise $franchise
     * @param \SOE\DB\Category $category
     * @param \SOE\DB\Category $subcategry
     */
    private function createLocationEntities($contest, $merchant, $franchise, $category, $subcategory)
    {
        $aLocs = $this->entities->query()
            ->where('entitiable_type', 'Contest')
            ->where('entitiable_id', $contest->id)
            ->lists('location_id');
        $aLocs[] = 0;
        $aAllLocs = $this->locations->query()
            ->where('franchise_id', $contest->franchise_id)
            ->lists('id');
        $addLocs = array_diff($aAllLocs, $aLocs);
        foreach($addLocs as $loc)
        {
            $location = $this->locations->find($loc);
            if(!$location)
                continue;
            $entity = $this->entities->create(array(
                'entitiable_id' => $contest->id,
                'entitiable_type' => 'Contest',
                'name' => $contest->name,
                'slug' => $contest->slug,
                'location_id' => $location->id,
                'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'path' => $contest->path,
                'is_dailydeal' => 0,
                'rating' => 0,
                'special_price' => 0,
                'regular_price' => 0,
                'is_demo' => $contest->is_demo,
                'is_active' => $contest->is_active,
                'starts_at' => $contest->starts_at,
                'expires_at' => $contest->expires_at,
                'rating_count' => 0,
                'savings' => 0,
                'url' => $contest->logo_link,
                'print_override' => '',
                'secondary_type' => $contest->type,
                'latm' => ($location->latitude*111133),
                'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                'merchant_name' => empty($merchant) ? '' : $merchant->display,
                'popularity' => 0,
                'is_featured' => $contest->is_featured,
                'state' => $location->state,
                'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                'starts_year' => date('Y', strtotime($contest->starts_at)),
                'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                'expires_year' => date('Y', strtotime($contest->expires_at)),
                'location_active' => $location->is_active,
                'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                'category_slug' => empty($category) ? '' : $category->slug,
                'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                'company_name' => empty($company) ? 0 : $company->name,
                'is_national' => $contest->is_national
            ));
        }
    }

    /**
     * Create entities for all independent Contest Locations.
     *
     * @param \SOE\DB\Contest $contest
     * @param \SOE\DB\Merchant $merchant
     * @param \SOE\DB\Franchise $franchise
     * @param \SOE\DB\Category $category
     * @param \SOE\DB\Category $subcategry
     */
    private function createIndependentLocationEntities($contest, $merchant, $franchise, $category, $subcategory)
    {
        $this->removeEntities($contest);
        $contestLocations = $this->contestLocations->getForContest($contest);
        foreach($contestLocations as $contestLocation)
        {
            $entity = $this->entities->create(array(
                'entitiable_id' => $contest->id,
                'entitiable_type' => 'Contest',
                'name' => $contest->name,
                'slug' => $contest->slug,
                'location_id' => $contest->id,
                'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                'latitude' => $contestLocation->latitude,
                'longitude' => $contestLocation->longitude,
                'path' => $contest->path,
                'is_dailydeal' => 0,
                'rating' => 0,
                'special_price' => 0,
                'regular_price' => 0,
                'is_demo' => $contest->is_demo,
                'is_active' => $contest->is_active,
                'starts_at' => $contest->starts_at,
                'expires_at' => $contest->expires_at,
                'rating_count' => 0,
                'savings' => 0,
                'url' => $contest->logo_link,
                'print_override' => '',
                'secondary_type' => $contest->type,
                'latm' => ($contestLocation->latitude*111133),
                'lngm' => (111133*cos(deg2rad($contestLocation->latitude))*$contestLocation->longitude),
                'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                'merchant_name' => empty($merchant) ? '' : $merchant->display,
                'popularity' => 0,
                'is_featured' => $contest->is_featured,
                'state' => $contestLocation->state,
                'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                'starts_year' => date('Y', strtotime($contest->starts_at)),
                'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                'expires_year' => date('Y', strtotime($contest->expires_at)),
                'location_active' => 1,
                'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                'category_slug' => empty($category) ? '' : $category->slug,
                'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                'company_name' => empty($company) ? 0 : $company->name,
                'is_national' => $contest->is_national,
                'service_radius' => $contestLocation->service_radius
            ));
        }
    }

    /**
     * Delete all entities associated with a contest.
     *
     * @param \SOE\DB\Contest $contest
     */
    private function removeEntities($contest)
    {
        $this->entities->query()
            ->where('entitiable_id', $contest->id)
            ->where('entitiable_type', 'Contest')
            ->delete();
    }

    /**
     * Get the number of applications per date for a specific contest
     */
    public function getApplicantDetails($contest_id, $start, $end)
    {
        // NOTE: this query must be custom due to using DATE()
        /* query

            SELECT DATE(created_at), COUNT(id)
            FROM contest_applications
            GROUP BY DATE(created_at);
         */

        $query = \SOE\DB\ContestApplication::where('contest_id', '=', $contest_id);
        if($start)
            $query = $query->where('created_at', '>=', $start);
        if($end)
            $query = $query->where('created_at', '<=', $end);
        $details = $query->groupBy(\DB::raw('DATE(created_ats)'))
                        ->count();
        /*$details = \DB::select("
            SELECT DATE(created_at) as date, COUNT(id) as count
            FROM contest_applications
            WHERE contest_id = ?
            GROUP BY DATE(created_at)
        ", array($contest_id));*/

        return $details;
    }

    /**
     * Retrive all applications for the given contest between the given start and end dates.
     *
     * @param SOE\DB\Contest    $contest
     * @param string            $start Date string, optional.
     * @param string            $end Date string, optional.
     * @return array
     */
    public function getApplicants(\SOE\DB\Contest $contest, $start = null, $end = null)
    {
        $query = \SOE\DB\ContestApplication::where('contest_id', '=', $contest->id);
        if($start)
            $query = $query->where('created_at', '>=', $start);
        if($end)
            $query = $query->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)));
        return $query->get();
    }

    /**
     * Retrieve all applications and user data for the given contest between the given start and end dates.
     *
     * @param int               $contest_id
     * @param string            $search Search string.
     * @param string            $start Date string, optional.
     * @param string            $end Date string, optional.
     * @param int               $page
     * @param int               $limit
     * @return array
     */
    public function getApplicantUsers($contest_id, $search = null, $start = null, $end = null, $page = 0, $limit = 0)
    {
        $query = \SOE\DB\ContestApplication::where('contest_applications.contest_id', '=', $contest_id)
                                            ->join('users', 'contest_applications.user_id', '=', 'users.id');
        if($start)
            $query = $query->where('contest_applications.created_at', '>=', date('Y-m-d 00:00:00', strtotime($start)));
        if($end)
            $query = $query->where('contest_applications.created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)));
        if($search)
        {
            $query = $query->where(function($query) use ($search)
                                {
                                    $query->where('users.name', 'LIKE', '%'.$search.'%');
                                    $query->orWhere('users.email', 'LIKE', '%'.$search.'%');
                                });
        }
        $query->groupBy('contest_applications.user_id');
        $stats = $this->getStats(clone $query, $limit, $page, true);
        if($limit)
        {
            $query = $query->take($limit)->skip($page*$limit);
        }
        $users = $query->get(array(
                                'contest_applications.*', 
                                \DB::raw('users.id as user_id'), 
                                \DB::raw('users.name as user_name'), 
                                \DB::raw('users.email as user_email'), 
                                \DB::raw('users.city as user_city'), 
                                \DB::raw('users.state as user_state')
                            ));
        $stats['stats']['returned'] = count($users);
        return array_merge(array('objects' => $users), $stats);
    }

    public function getWinners($contest_id)
    {
        $query = \SOE\DB\ContestWinner::where('contest_id', '=', $contest_id)
            ->leftJoin('users', 'contest_winners.user_id', '=', 'users.id')
            ->whereNotNull('contest_winners.verified_at');
        $users = $query->get(array(
            'contest_winners.*', 
            \DB::raw('users.email as user_email')
        ));
        foreach($users as &$user)
        {
            $user->city = ucwords(strtolower($user->city));
            $user->state = strtoupper($user->state);
            $user->first_name = ucwords(strtolower($user->first_name));
            $user->last_name = ucwords(strtolower($user->last_name));
        }
        $stats['stats']['returned'] = count($users);
        return array_merge(array('objects' => $users), $stats);
    }

    public function getAllWinners($userSpecific = false, $contestSearch = null, $contestMerchantSearch = null, $orderBy = null, $orderByOrder = null)
    {
        $query = \SOE\DB\Entity::where('entitiable_type','=','Contest');
        $query = $query->join('contest_winners', 'contest_winners.contest_id', '=', 'entities.entitiable_id')
            ->whereNotNull('contest_winners.verified_at');
        if ($userSpecific == true)
        {
            $query = $query->join('contest_applications', 'contest_applications.contest_id', '=', 'entities.entitiable_id');
            $query = $query->where('contest_applications.user_id', '=', \Auth::User()->id);
        }
        $query = $query->leftJoin('contest_disclaimers', 'contest_disclaimers.contest_id', '=', 'entities.entitiable_id');
        $query = $query->leftJoin('contests', 'contests.id', '=', 'entities.entitiable_id');
        $query = $query->groupBy('entities.entitiable_id');
        if($contestSearch)
        {
            $query = $query->where('contests.display_name','LIKE',$contestSearch.'%');
        }
        if($contestMerchantSearch)
        {
            $query = $query->where('entities.merchant_name','LIKE',$contestMerchantSearch.'%');
        }
        if($orderBy)
        {
            if($orderBy == 'date')
            {
                $query = $query->orderBy('contest_winners.verified_at',$orderByOrder);
            }
            elseif($orderBy == 'contestName')
            {
                $query = $query->orderBy('contests.display_name',$orderByOrder);
            }
            elseif($orderBy == 'merchantName')
            {
                $query = $query->orderBy('entities.merchant_name',$orderByOrder);
            }
            elseif($orderBy == 'winnerLastName')
            {
                $query = $query->orderBy('contest_winners.last_name',$orderByOrder);
            }
        } else {
            $query = $query->orderBy('contest_winners.verified_at','desc');
        }
        $entities = $query->get(array(
            'entities.*',
            'contest_disclaimers.*',
            \DB::raw('contest_winners.user_id as winner_user_id'),
            \DB::raw('contest_winners.first_name as winner_first_name'),
            \DB::raw('contest_winners.last_name as winner_last_name'),
            \DB::raw('contest_winners.city as winner_city'),
            \DB::raw('contest_winners.state as winner_state'),
            \DB::raw('contest_winners.verified_at as winner_state_verified_at'),
        ));
        $aContIDs = array(0);
        foreach($entities as $entity)
        {
            $aIDs[] = $entity->location_id;
            if($entity->entitiable_type == 'Contest')
            {
                $aContIDs[] = $entity->entitiable_id;
            }
        }
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->get(array('id', 'display_name'));
        $aContNames = array();
        foreach($contests as $contest)
        {
            $aContNames[$contest->id] = $contest->display_name;
        }
        $totalWinners = \SOE\DB\Contest::whereIn('contests.id', $aContIDs)
            ->join('contest_winners', 'contests.id', '=', 'contest_winners.contest_id')
            ->whereNotNull('verified_at')
            ->groupBy('contests.id')
            ->get(array('contests.id', \DB::raw('count(*) as total_winners')));
        $aTotals = array();
        foreach($totalWinners as $total)
        {
            $aTotals[$total->id] = $total->total_winners;
        }
        foreach($entities as $entity)
        {
            $entity->display_name = ($entity->entitiable_type == 'Contest' && isset($aContNames[$entity->entitiable_id])) ? $aContNames[$entity->entitiable_id] : '';
            $entity->winner_first_name = ucwords(strtolower($entity->winner_first_name));
            $entity->winner_last_name = ucwords(strtolower($entity->winner_last_name));
            $entity->winner_city = ucwords(strtolower($entity->winner_city));
            $entity->winner_count = ($entity->entitiable_type == 'Contest' && isset($aTotals[$entity->entitiable_id])) ? $aTotals[$entity->entitiable_id] : 0;
        }
        $stats['stats']['returned'] = count($entities);
        return array_merge(array('objects' => $entities), $stats);
    }

    public function getAllWinnersDetail($contestSearch = null, $contestMerchantSearch = null, $contestEmailSearch = null, $contestLastNameSearch = null, $orderBy = null, $orderByOrder = null)
    {
        $query = \SOE\DB\ContestWinner::join('users', 'contest_winners.user_id', '=', 'users.id')
            ->whereNotNull('contest_winners.verified_at');
        $query = $query->join('contests', 'contests.id', '=', 'contest_winners.contest_id');
        $query = $query->join('merchants', 'merchants.id', '=', 'contests.merchant_id');
        $query = $query->leftJoin('contest_disclaimers', 'contest_disclaimers.contest_winner_id', '=', 'contest_winners.id');
        if($contestSearch)
        {
            $query = $query->where('contests.display_name','LIKE',$contestSearch.'%');
        }
        if($contestMerchantSearch)
        {
            $query = $query->where('merchants.name','LIKE',$contestMerchantSearch.'%');
        }
        if($contestEmailSearch)
        {
            $query = $query->where('contest_winners.email','LIKE',$contestEmailSearch.'%');
        }
        if($contestLastNameSearch)
        {
            $query = $query->where('contest_winners.last_name','LIKE',$contestLastNameSearch.'%');
        }
        if($orderBy)
        {
            if($orderBy == 'date')
            {
                $query = $query->orderBy('contest_winners.verified_at',$orderByOrder);
            }
            elseif($orderBy == 'contestName')
            {
                $query = $query->orderBy('contests.display_name',$orderByOrder);
            }
            elseif($orderBy == 'merchantName')
            {
                $query = $query->orderBy('merchants.name',$orderByOrder);
            }
            elseif($orderBy == 'winnerLastName')
            {
                $query = $query->orderBy('contest_winners.last_name',$orderByOrder);
            }
        } else {
            $query = $query->orderBy('contest_winners.verified_at','desc');
        }
        $entities = $query->get(array(
            'contests.*',
            'contest_disclaimers.*',
            \DB::raw('contests.id as contest_id'),
            \DB::raw('merchants.name as merchant_name'),
            \DB::raw('contest_winners.id as winner_id'),
            \DB::raw('contest_winners.user_id as winner_user_id'),
            \DB::raw('contest_winners.first_name as winner_first_name'),
            \DB::raw('contest_winners.last_name as winner_last_name'),
            \DB::raw('contest_winners.address as winner_address'),
            \DB::raw('contest_winners.city as winner_city'),
            \DB::raw('contest_winners.state as winner_state'),
            \DB::raw('contest_winners.zip as winner_zip'),
            \DB::raw('contest_winners.verified_at as winner_state_verified_at'),
        ));
        $stats['stats']['returned'] = count($entities);
        return array_merge(array('objects' => $entities), $stats);
    }

    public function getWinnerInfo($winner_id)
    {
        $query = \SOE\DB\ContestWinner::where('contest_winners.id', '=', $winner_id)
            ->leftJoin('users', 'contest_winners.user_id', '=', 'users.id')
            ->whereNotNull('contest_winners.verified_at');
        $query = $query->leftJoin('contests', 'contests.id', '=', 'contest_winners.contest_id');
        $query = $query->leftJoin('contest_disclaimers', 'contest_disclaimers.contest_id', '=', 'contests.id');
        $winner = $query->first(array(
            'contest_winners.*', 
            \DB::raw('users.email as user_email'),
            'contests.id as contest_id',
            'contests.display_name as display_name'
        ));
        return $winner;
    }

    /**
     * Get all contests that belong to the given merchant id, optionally filter our expired contests.
     *
     * @param int       $merchant_id
     * @param boolean   $expired Whether or not to display expired contests, default false.
     * @return array
     */
    public function getByMerchantId($merchant_id, $expired = false)
    {
        $query = \SOE\DB\Contest::where('merchant_id', '=', $merchant_id)
                            ->orderBy('display_name');
        if(!$expired)
        {
            $query = $query->where('expires_at', '>=', \DB::raw('NOW()'));
        }
        return $query->get();
    }

    public function getNearbyContests($user_id, $page = 0, $limit = 12)
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $user = $this->userRepository->find($user_id);
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $entries = \SOE\DB\ContestApplication::where('user_id', '=', $user_id)->get(array('contest_id'));
        $aEntryIds = array(0);
        foreach($entries as $entry)
        {
            $aEntryIds[] = $entry->contest_id;
        }
        $aStates = $this->zipcodeRepository->getSurroundingStates($geoip->latitude, $geoip->longitude);
        $query = \SOE\DB\Entity::whereIn('state', $aStates)
                                ->whereNotIn('entitiable_id', $aEntryIds)
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
                                ->where('entitiable_type', '=', 'Contest');
        $showDemo = $this->userRepository->showDemo($user);
        if(!$showDemo)
        {
            $query = $query->where('entities.is_demo', '=', '0')
                            ->where('entities.franchise_demo', '=', '0');
        }
        if($limit)
        {
            $query = $query->skip($page*$limit)->take($limit);
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        $contests = $query->orderBy('distance', 'asc')
                            ->get(array('entities.*', \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $stats['stats']['returned'] = count($contests);
        $return = array('objects' => array());
        foreach($contests as $contest)
        {
            $return['objects'][] = $contest;
        }
        return array_merge($return, $stats);
    }

    /**
     * Get nearby active contests that the given user has not entered.
     *
     * @param int       $user_id
     * @param float     $latitude
     * @param float     $longitude
     * @param int       $page
     * @param int       $limit
     * @return array
     */
    public function getNearbyOpen($user_id, $latitude, $longitude, $page = 0, $limit = 0)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $query = \SOE\DB\Entity::join('contests', 'entities.entitiable_id', '=', 'contests.id')
                           ->leftJoin(\DB::raw('(SELECT contests.* from contests inner join contest_applications on contests.id = contest_applications.contest_id where contest_applications.user_id = '.$user_id.') entered'), 'contests.id', '=', 'entered.id')
                           ->whereNull('entered.id')
                           ->where('entities.entitiable_type', '=', 'Contest')
                           ->where(\DB::raw('(sqrt(pow(entities.latm - '.$cartesian["latm"].', 2) + pow(entities.lngm - '.$cartesian["lngm"].', 2)))'), '<', 48000)
                           ->where('entities.is_active', '=', '1')
                           ->where('entities.is_demo', '=', '0')
                           ->where(function($query)
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
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query = $query->skip($page*$limit)->take($limit);
        $contests = $query->groupBy('contests.id')
                           ->get(array('entities.*', 'contests.display_name'));
        $stats['stats']['returned'] = count($contests);
        $objects = array('objects' => array());
        foreach($contests as $contest)
        {
            $objects['objects'][] = $contest;
        }
        return array_merge($objects, $stats);
    }

    /**
     * Retrieve the count of contest applications for the given contest during the given date range.
     *
     * @param int   $contest_id
     * @param date  $start
     * @param date  $end
     * @return int
     */
    public function getApplicantCount($contest_id = null, $start = null, $end = null, $market = null)
    {
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query = \SOE\DB\Entity::where('entitiable_type', 'Contest')
                    ->where('state', $abbr)
                    ->groupBy('entitiable_id');
                if($end)
                    $query->where('starts_at', '<=', $end);
                if($start)
                    $query->where('expires_at', '>=', $start);
                $contests = $query->remember(60*12)
                    ->get(array('entitiable_id'));
                $aContestIds = array(0);
                foreach($contests as $contest)
                    $aContestIds[] = $contest->entitiable_id;
                $applicants = \SOE\DB\ContestApplication::whereIn('contest_id', $aContestIds);
                if($start)
                {
                    $applicants->where('created_at', '>=', $start);
                }
                if($end)
                {
                    $applicants->where('created_at', '<=', $end);
                }
                return $applicants->remember(60*12)->count();
            }
        }
        $applicants = \SOE\DB\ContestApplication::query();
        if($contest_id)
        {
            $applicants = $applicants->where('contest_id', '=', $contest_id);
        }
        if($start)
        {
            $applicants = $applicants->where('created_at', '>=', $start);
        }
        if($end)
        {
            $applicants = $applicants->where('created_at', '<=', $end);
        }
        return $applicants->remember(60*12)->count();
    }

    public function getContestsRun($startDate = null, $endDate = null, $market = null)
    {
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query = $this->query()
                    ->join('entities', function($join)
                    {
                        $join->on('contests.id', '=', 'entities.entitiable_id')
                            ->on('entities.entitiable_type', '=', \DB::raw("'Contest'"));
                    })
                    ->where('entities.state', $abbr)
                    ->groupBy('contests.id');
                if($endDate)
                    $query->where('contests.starts_at', '<=', $endDate);
                if($startDate)
                    $query->where('contests.expires_at', '>=', $startDate);
                return $query->get(array('contests.*'));
            }
        }
        $query = $this->query();
        if($endDate)
            $query->where('starts_at', '<=', $endDate);
        if($startDate)
            $query->where('expires_at', '>=', $startDate);
        return $query->get();
    }

    public function sendEndingEmail($contest_id, $test_email = null)
    {
        $contest = $this->with('merchant', 'winners', 'applications')
                        ->where('id', $contest_id)
                        ->first();
        if($contest->follow_up_sent_at != NULL)
            return;

        $aWin = array();
        foreach($contest->winners as $winner)
        {
            $aWin[$winner->user_id] = array(
                'first' => $winner->first_name, 
                'last' => $winner->last_name, 
                'city' => $winner->city, 
                'state' => $winner->state
            );
        }
        $aContest = array(
            'name' => $contest->display_name,
            'banner' => $contest->banner,
            'text' => $contest->follow_up_text
        );
        
        if($contest->follow_up_id == 0)
            return false;
        
        $follow = $contest->followUp;
        $entity = $follow->entities()->first();
        if(empty($entity))
        {
            return false;
        }
        $location = $this->locations->find($entity->location_id);
        if(!$location)
            return false;
        $data = array(
            'winners' => $aWin, 
            'contest' => $aContest, 
            'merchant' => $contest->merchant->toArray(), 
            'entity' => $entity->toArray(),
            'location' => $location->toArray()
        );
        if(!$test_email)
        {
            foreach($contest->applications as $application)
            {
                if(!isset($aWin[$application->user_id]))
                {
                    $email = $application->email;
                    \Mail::queueOn('SOE_Tasks', 'emails.contestended', $data, function($message) use ($email)
                    {
                        $message->to($email)->subject('Everybody Wins At SaveOn!');
                    });
                }
            }
            $contest->follow_up_sent_at = date('Y-m-d H:i:s');
            $contest->save();
        }
        else
        {
            \Mail::queueOn('SOE_Tasks', 'emails.contestended', $data, function($message) use ($test_email)
            {
                $message->to($test_email)->subject('Everybody Wins At SaveOn!');
            });
        }
        return $contest;
    }

    public function isEntered($contest_id, $user_id)
    {
        return \SOE\DB\ContestApplication::where('contest_id', $contest_id)->where('user_id', $user_id)->first();
    }

    public function getSweepstakes($entity_id)
    {
        /**
        * If  the contest has an intenral/external secondary type, it will be a sweepstakes.
        * Win 5k is a type of sweepstakes, which features a different modal than normal contests.
        * The input is a user's unique 6-digit code.
        * With internal sweepstakes, every day, a winning 6 digit code is selected.
        * If the member's code matches the winning code, he/she can submit a claim form.
        * With external sweepstakes, winning numbers will display on the site.
        * The member's number will display on an external form or code (found in a commercial or SaveOn magazine)
        */
        $winner = \DB::table('sweepstakes')->where('entity_id', '=', $entity_id)
                                    ->where('date', '=', date('Y-m-d 00:00:00'))
                                    ->where('randomnum', '!=', '')
                                    ->first();
        $entity = $this->entities->find($entity_id);
        if(empty($winner))
        {
            $contest = \SOE\DB\Contest::where('id', '=', $entity->entitiable_id)->first();
            $randomnum = rand($contest->winning_number_min, ($contest->winning_number_max ? $contest->winning_number_max : pow(10, $contest->winning_number_length+1)-1));
            $randomnum = $contest->winning_number_type == 'alphanum' ? \PseudoCrypt::hash($randomnum, $contest->winning_number_length) : str_pad($randomnum, $contest->winning_number_length, '0', STR_PAD_LEFT);

            \DB::table('sweepstakes')->insert(array(
                'date' => date('Y-m-d 00:00:00'),
                'randomnum' => $randomnum,
                'entity_id' => $entity->id
            ));
        }
        else
        {
            $randomnum = $winner->randomnum;
        }

        return $randomnum;
    }

    public function statReport($franchise_id, $location_id = 0)
    {
        $franchise = \SOE\DB\Franchise::find($franchise_id);
        $contests = $this->query()->leftJoin(\DB::raw(
            "(select count(*) as apps, applicants.contest_id from (select * from contest_applications
                group by contest_id, user_id) applicants group by applicants.contest_id) total_apps"), 'contests.id', '=', 'total_apps.contest_id')
            ->where(function($query) use ($franchise)
            {
                $query->where('franchise_id', $franchise->id);
                $query->orWhere('merchant_id', $franchise->merchant_id);
            })
            ->where('is_demo', 0)
            ->where('is_active', 1)
            ->orderBy('expires_at', 'desc')
            ->take(12)
            ->get(array('total_apps.apps', 'contests.*'));

        foreach ($contests as $contest)
        {
            $aWin = array();
            $contestData = $this->with('merchant', 'winners', 'applications')
                ->find($contest->id);

            foreach($contestData->winners as $winner)
            {
                $aWin[$winner->user_id] = array(
                    'first' => $winner->first_name, 
                    'last' => $winner->last_name, 
                    'city' => $winner->city, 
                    'state' => $winner->state
                );
            }
            $aContest = array(
                'name' => $contest->display_name,
                'banner' => $contest->banner,
                'text' => $contest->follow_up_text
            );
            
            $data = array(
                'winners' => $aWin, 
                'contest' => $aContest, 
                'merchant' => $contest->merchant->toArray()
            );
            if($contest->follow_up_type != '')
            {
                $follow = $contest->followUp;
                if($follow)
                {
                    $entity = $follow->entities()->first();
                    $location = $this->locations->find($entity->location_id);
                    $data['entity'] = $entity->toArray();
                    $data['location'] = $location->toArray();
                }
            }
            $contest->data = $data;
        }
        return array('objects' => $contests->toArray());
    }

}
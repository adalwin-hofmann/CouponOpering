<?php

class EloquentContestRepository extends BaseEloquentRepository implements ShareableInterface, RepositoryInterface
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
        'is_location_independent'
    );

    protected $model = 'Contest';

    /**
     * Fill out application for this Contest.
     *
     * @param  mixed $applicant Either a UserRepository or a Nonmember Repository
     * @return mixed
     */
    public function apply($applicant)
    {
        /**
        * Entries are stored in contest_applications table of DB
        * The contests themselves populate from the contests table and entities table of the DB.
        * Whenever a contest is created, it creates an entity or entities that correspond to that contest.
        * Contests are also related to a merchant or location, and thus have a set of coordinates.
        * These coordinates determine whether a member sees these contests.
        * Each contest has an expiration date,
        * flagging active (accessbile to all users),
        * flagging inactive (entity is expired),
        * or flagging demo (only visible if user has role employee or role demo).
        * If flagged as featured, can show up in dropdown or in category banner.
        */

        if($this->primary_key)
        {
            return ContestApplication::fillOut($this, $applicant);
        }
        return false;
    }

    /**
     * Share this contest for a given user.
     *
     * @param UserRepository    $sharer
     * @param string            $type The type of share, email or facebook.
     * @param array             $params An array of parameters.
     *
     * @return Share
     */
    public function share(UserRepository $sharer, $type, $params = array())
    {
        if($this->primary_key)
        {
            //TODO: Implement sharing.
        }
    }

    public function getSweepstakes(EntityRepository $entity)
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
        $winner = DB::table('sweepstakes')->where('entity_id', '=', $entity->id)
                                    ->where('date', '=', date('Y-m-d 00:00:00'))
                                    ->where('randomnum', '!=', '')
                                    ->first();
        if(empty($winner))
        {
            $contest = \SOE\DB\Contest::where('id', '=', $entity->entitiable_id)->first();
            $randomnum = rand($contest->winning_number_min, ($contest->winning_number_max ? $contest->winning_number_max : pow(10, $contest->winning_number_length+1)-1));
            $randomnum = $contest->winning_number_type == 'alphanum' ? PseudoCrypt::hash($randomnum, $contest->winning_number_length) : str_pad($randomnum, $contest->winning_number_length, '0', STR_PAD_LEFT);
            /*if($entity->secondary_type == 'external')
            {
                $randomnum = rand(2000,900000);
            }
            else
            {
                $randomnum = PseudoCrypt::hash(rand(1,999999), 6);
            }*/
            DB::table('sweepstakes')->insert(array(
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

    /**
     * Insert an application for win5k.
     *
     * @param string    $location
     * @param array     $application Array of application data.
     * @return mixed
     */
    public function applySweepstakes(EntityRepository $entity, $application)
    {
        $randomnum = $this->getSweepstakes($entity);
        $user = User::find($application['user_id']);
        $app = DB::table('sweepstakes')->insert(array(
            'entity_id' => $entity->id,
            'randomnum' => $randomnum,
            'firstname' => $application['firstname'],
            'lastname' => $application['lastname'],
            'phone' => $application['phone'],
            'address1' => $application['address1'],
            'address2' => $application['address2'],
            'city' => $application['city'],
            'state' => $application['state'],
            'zipcode' => $application['zipcode'],
            'magazinenum' => $application['magazinenum'] == '' ? $user->win5kid : $application['magazinenum'],
            'user_id' => $application['user_id']
        ));

        return $app;
    }

    /**
     * Get Sales Report
     *
     * Retrieves a list of models used in the contest report
     *
     * @param int $page
     * @param int $limit
     * @param array $orderby = array('name'=>'m.name', 'dir'=>'desc')
     * @param string $search The search string entered by the user
     * @param bool $show_expired Include expired contests (default false)
     */
    public function getSalesReport(
        $page = 0,
        $limit = 20,
        $orderby = array(),
        $search = null,
        $show_expired = false
    )
    {
        /* Query Details
        select m.name, c.display_name, count(apps.id)
        from contests as c
        join merchants as m on m.id = c.merchant_id
        join contest_applications as apps on apps.contest_id = c.id
        where c.id = 14;
        */

        $builder = SOE\DB\Contest::select(
                'contests.id',
                'contests.name',
                'contests.display_name',
                'contests.starts_at',
                'contests.expires_at',
                'merchants.name as merchant_name',
                //DB::raw('COUNT(contest_applications.id) as applicants')
                DB::raw('COUNT(DISTINCT(contest_applications.user_id)) as applicants')
            )
            ->leftJoin('contest_applications', 'contest_id', '=', 'contests.id')
            ->leftJoin('merchants', 'merchants.id', '=', 'contests.merchant_id');

        if (isset($search)) {
            $builder->where(function($query) use ($search) {
                $query->where('contests.display_name', 'LIKE', "%$search%")
                    ->orWhere('merchants.name', 'LIKE', "%$search%")
                    ->orWhere('contests.name', 'LIKE', "%$search%");
            });
        }

        if ($orderby) {
            $builder->orderBy($orderby['name'], $orderby['dir']);
        } else {
            $builder->orderBy('contests.expires_at', 'asc');
        }

        if ($show_expired) {
            // TODO: make sure this is correct
            $builder->where('contests.is_active', '=', '1');
        }

        $builder->groupBy('contests.id');

        return $builder->paginate($limit);
    }

    /**
     * Get the number of applications per date for a specific contest
     */
    public function getApplicantDetails($contest_id)
    {
        // NOTE: this query must be custom due to using DATE()
        /* query

            SELECT DATE(created_at), COUNT(id)
            FROM contest_applications
            GROUP BY DATE(created_at);
         */

        $details = DB::select("
            SELECT DATE(created_at) as date, COUNT(id) as count
            FROM contest_applications
            WHERE contest_id = ?
            GROUP BY DATE(created_at)
        ", array($contest_id));

        return $details;
    }

    /**
     * Create an entity for this contest.
     *
     * @return Entity
     */
    public function createEntity()
    {
        if(!$this->primary_key)
            return;

        $existing = $this->entity();
        if(!empty($existing))
            return;

        $merchant = Merchant::find($this->merchant_id);
        $category = empty($merchant) ? array() : $merchant->category();
        $subcategory = empty($merchant) ? array() : $merchant->subcategory();
        $franchise = $this->franchise();
        $locations = empty($franchise) ? array() : $franchise->locations();
        $company = empty($franchise) ? array() : $franchise->company();
        $zip = Zipcode::getNearest($this->latitude, $this->longitude);
        if(empty($locations))
        {
            $entity = Entity::create(array(
                'entitiable_id' => $this->id,
                'entitiable_type' => 'Contest',
                'name' => $this->name,
                'slug' => $this->slug,
                'location_id' => $this->id,
                'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'path' => $this->path,
                'is_dailydeal' => 0,
                'rating' => 0,
                'special_price' => 0,
                'regular_price' => 0,
                'is_demo' => $this->is_demo,
                'is_active' => $this->is_active,
                'starts_at' => $this->starts_at,
                'expires_at' => $this->expires_at,
                'rating_count' => 0,
                'savings' => 0,
                'url' => $this->logo_link,
                'print_override' => '',
                'secondary_type' => $this->type,
                'latm' => ($this->latitude*111133),
                'lngm' => (111133*cos(deg2rad($this->latitude))*$this->longitude),
                'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                'merchant_name' => empty($merchant) ? '' : $merchant->display,
                'popularity' => 0,
                'is_featured' => $this->is_featured,
                'state' => $zip->state,
                'starts_day' => date('z', strtotime($this->starts_at)) + 1,
                'starts_year' => date('Y', strtotime($this->starts_at)),
                'expires_day' => date('z', strtotime($this->expires_at)) + 1,
                'expires_year' => date('Y', strtotime($this->expires_at)),
                'location_active' => 1,
                'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                'category_slug' => empty($category) ? '' : $category->slug,
                'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                'company_name' => empty($company) ? 0 : $company->name,
                'is_national' => $this->is_national
            ));
        }
        else
        {
            foreach($locations['objects'] as $location)
            {
                $entity = Entity::create(array(
                'entitiable_id' => $this->id,
                'entitiable_type' => 'Contest',
                'name' => $this->name,
                'slug' => $this->slug,
                'location_id' => $location->id,
                'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'path' => $this->path,
                'is_dailydeal' => 0,
                'rating' => 0,
                'special_price' => 0,
                'regular_price' => 0,
                'is_demo' => $this->is_demo,
                'is_active' => $this->is_active,
                'starts_at' => $this->starts_at,
                'expires_at' => $this->expires_at,
                'rating_count' => 0,
                'savings' => 0,
                'url' => $this->logo_link,
                'print_override' => '',
                'secondary_type' => $this->type,
                'latm' => ($location->latitude*111133),
                'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                'merchant_name' => empty($merchant) ? '' : $merchant->display,
                'popularity' => 0,
                'is_featured' => $this->is_featured,
                'state' => $location->state,
                'starts_day' => date('z', strtotime($this->starts_at)) + 1,
                'starts_year' => date('Y', strtotime($this->starts_at)),
                'expires_day' => date('z', strtotime($this->expires_at)) + 1,
                'expires_year' => date('Y', strtotime($this->expires_at)),
                'location_active' => $location->is_active,
                'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                'category_slug' => empty($category) ? '' : $category->slug,
                'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                'company_name' => empty($company) ? 0 : $company->name,
                'is_national' => $this->is_national
            ));
            }
        }

        return $entity;
    }

    /**
     * Update the entity for this contest to sync it with contest data.
     *
     * @return void
     */
    public function updateEntity()
    {
        if(!$this->primary_key)
            return;

        $merchant = Merchant::find($this->merchant_id);
        $category = empty($merchant) ? array() : $merchant->category();
        $subcategory = empty($merchant) ? array() : $merchant->subcategory();
        $franchise = $this->franchise();
        $locations = empty($franchise) ? array() : $franchise->locations();
        $company = empty($franchise) ? array() : $franchise->company();
        $zip = Zipcode::getNearest($this->latitude, $this->longitude);
        if(empty($locations))
        {
            // If this used to be associated with a merchant, delete the old entities
            $merchantEntities = \SOE\DB\Entity::where('entitiable_id', $this->id)
                ->where('entitiable_type', 'Contest')
                ->where('merchant_id', '!=', '0')
                ->count();
            if($merchantEntities)
            {
                \SOE\DB\Entity::where('entitiable_id', $this->id)
                ->where('entitiable_type', 'Contest')
                ->where('merchant_id', '!=', '0')
                ->delete();
            }

            $existing = $this->entity();
            if(!empty($existing))
            {
                // Just updating
                SOE\DB\Entity::where('entitiable_id', '=', $this->id)
                            ->where('entitiable_type', '=', 'Contest')
                            ->update(array(
                                'name' => $this->name,
                                'slug' => $this->slug,
                                'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                                'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                                'latitude' => $this->latitude,
                                'longitude' => $this->longitude,
                                'path' => $this->path,
                                'is_demo' => $this->is_demo,
                                'is_active' => $this->is_active,
                                'starts_at' => $this->starts_at,
                                'expires_at' => $this->expires_at,
                                'url' => $this->logo_link,
                                'secondary_type' => $this->type,
                                'latm' => ($this->latitude*111133),
                                'lngm' => (111133*cos(deg2rad($this->latitude))*$this->longitude),
                                'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                                'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                                'merchant_name' => empty($merchant) ? '' : $merchant->display,
                                'is_featured' => $this->is_featured,
                                'state' => $zip->state,
                                'starts_day' => date('z', strtotime($this->starts_at)) + 1,
                                'starts_year' => date('Y', strtotime($this->starts_at)),
                                'expires_day' => date('z', strtotime($this->expires_at)) + 1,
                                'expires_year' => date('Y', strtotime($this->expires_at)),
                                'location_active' => 1,
                                'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                                'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                                'category_slug' => empty($category) ? '' : $category->slug,
                                'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                                'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                                'company_name' => empty($company) ? 0 : $company->name,
                                'is_national' => $this->is_national
                            ));
            }
            else
            {
                $this->createEntity();
            }
        }
        else
        {
            $existing = SOE\DB\Entity::where('entitiable_id', '=', $this->id)
                                    ->where('entitiable_type', '=', 'Contest')
                                    ->first();
            if(!empty($existing))
            {
                $location = SOE\DB\Location::where('id', '=', $existing->location_id)->first();
                if(!empty($location) && $location->franchise_id != $franchise->id)
                {
                    // Franchise has changed, remove old entities.
                    SOE\DB\Entity::where('entitiable_id', '=', $this->id)
                                ->where('entitiable_type', '=', 'Contest')
                                ->delete();

                    // Create new entites for the new franchise's locations
                    foreach($locations['objects'] as $location)
                    {
                        $entity = Entity::create(array(
                            'entitiable_id' => $this->id,
                            'entitiable_type' => 'Contest',
                            'name' => $this->name,
                            'slug' => $this->slug,
                            'location_id' => $location->id,
                            'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                            'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                            'latitude' => $location->latitude,
                            'longitude' => $location->longitude,
                            'path' => $this->path,
                            'is_dailydeal' => 0,
                            'rating' => 0,
                            'special_price' => 0,
                            'regular_price' => 0,
                            'is_demo' => $this->is_demo,
                            'is_active' => $this->is_active,
                            'starts_at' => $this->starts_at,
                            'expires_at' => $this->expires_at,
                            'rating_count' => 0,
                            'savings' => 0,
                            'url' => $this->logo_link,
                            'print_override' => '',
                            'secondary_type' => $this->type,
                            'latm' => ($location->latitude*111133),
                            'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                            'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                            'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                            'merchant_name' => empty($merchant) ? '' : $merchant->display,
                            'popularity' => 0,
                            'is_featured' => $this->is_featured,
                            'state' => $location->state,
                            'starts_day' => date('z', strtotime($this->starts_at)) + 1,
                            'starts_year' => date('Y', strtotime($this->starts_at)),
                            'expires_day' => date('z', strtotime($this->expires_at)) + 1,
                            'expires_year' => date('Y', strtotime($this->expires_at)),
                            'location_active' => $location->is_active,
                            'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                            'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                            'category_slug' => empty($category) ? '' : $category->slug,
                            'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                            'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                            'company_name' => empty($company) ? 0 : $company->name,
                            'is_national' => $this->is_national
                        ));
                    }
                }
                else if(!empty($location) && $location->franchise_id == $franchise->id)
                {
                    // Franchise has not changed, update existing entities.
                    foreach($locations['objects'] as $location)
                    {
                        SOE\DB\Entity::where('entitiable_id', '=', $this->id)
                                ->where('entitiable_type', '=', 'Contest')
                                ->where('location_id', '=', $location->id)
                                ->update(array(
                                    'name' => $this->name,
                                    'slug' => $this->slug,
                                    'category_id' => empty($merchant) ? 0 : $merchant->category_id,
                                    'subcategory_id' => empty($merchant) ? 0 : $merchant->subcategory_id,
                                    'latitude' => $location->latitude,
                                    'longitude' => $location->longitude,
                                    'path' => $this->path,
                                    'is_demo' => $this->is_demo,
                                    'is_active' => $this->is_active,
                                    'starts_at' => $this->starts_at,
                                    'expires_at' => $this->expires_at,
                                    'url' => $this->logo_link,
                                    'secondary_type' => $this->type,
                                    'latm' => ($location->latitude*111133),
                                    'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                                    'merchant_id' => empty($merchant) ? 0 : $merchant->id,
                                    'merchant_slug' => empty($merchant) ? '' : $merchant->slug,
                                    'merchant_name' => empty($merchant) ? '' : $merchant->display,
                                    'is_featured' => $this->is_featured,
                                    'state' => $location->state,
                                    'starts_day' => date('z', strtotime($this->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($this->starts_at)),
                                    'expires_day' => date('z', strtotime($this->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($this->expires_at)),
                                    'location_active' => $location->is_active,
                                    'franchise_active' => empty($franchise) ? 1 : $franchise->is_active,
                                    'franchise_demo' => empty($franchise) ? 0 : $franchise->is_demo,
                                    'category_slug' => empty($category) ? '' : $category->slug,
                                    'subcategory_slug' => empty($subcategory) ? '' : $subcategory->slug,
                                    'company_id' => empty($franchise) ? 0 : $franchise->company_id,
                                    'company_name' => empty($company) ? 0 : $company->name,
                                    'is_national' => $this->is_national
                                ));
                    }
                }
            }
        }


    }

    /**
     * Get a list of contests matching a name query.
     *
     * @param string    $name
     * @param int       $page
     * @param int       $limit
     *
     * @return array Contests
     */
    public function getByName($name, $page = 0, $limit = 0)
    {
        $name = str_replace("'", '', $name);
        $query = SOE\DB\Contest::where(function($query) use ($name)
                                {
                                    $query->where('contests.name', 'LIKE', '%'.$name.'%');
                                    $query->orWhere('contests.display_name', 'LIKE', '%'.$name.'%');
                                })
                                ->orderBy('contests.name', 'asc');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $contests = $query->get();
        $stats['stats']['returned'] = count($contests);
        $return = array('objects' => array());
        foreach($contests as $contest)
        {
            $franch = Contest::blank();
            $franch = $franch->createFromModel($contest);
            $return['objects'][] = $franch;
        }

        return array_merge($return, $stats);
    }

    /**
     * Each contest has one entity.
     *
     * @return Entity
     */
    public function entity()
    {
        if($this->primary_key)
        {
            $entity = SOE\DB\Entity::where('entitiable_id', '=', $this->id)
                                    ->where('entitiable_type', '=', 'Contest')
                                    ->first();

            if(empty($entity))
                return;
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);

            return $ent;
        }
    }

    /**
     * Each contest belongs to a Franchise.
     *
     * @return Franchise
     */
    public function franchise()
    {
        if($this->primary_key)
        {
            return Franchise::find($this->franchise_id);
        }
    }

    /*public function apiGetByUser($user)
    {
        $person = Auth::check() ? Auth::User() : Nonmember::blank();
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $state = Input::get('state', $geoip->region_name);
        $type = Input::get('type', null);
        $category_id = Input::get('category_id');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getByUser($person, $state, $lat, $lng, $type, $category_id, $sort, $page, $limit));
    }*/

    /***** API METHODS *****/

    /**
     * Get the winning Sweepstakes numbers based on entity_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetSweepstakes()
    {
        $entity_id = Input::get('entity_id');
        $entity = Entity::find($entity_id);
        return $this->format($this->getSweepstakes($entity));
    }

    /**
     * Apply for win5k based on entity_id, user_id, firstname, lastname, phone, address1, address2, city, state, zipcode, magazinenum.
     *
     * @api
     *
     * @return mixed
     */
    public function apiApplySweepstakes()
    {
        $application = array();
        $application['firstname'] = Input::get('firstname', '');
        $application['lastname'] = Input::get('lastname', '');
        $application['phone'] = Input::get('phone', '');
        $application['address1'] = Input::get('address1', '');
        $application['address2'] = Input::get('address2', '');
        $application['city'] = Input::get('city', '');
        $application['state'] = Input::get('state', '');
        $application['zipcode'] = Input::get('zipcode', '');
        $application['magazinenum'] = Input::get('magazinenum', '');
        $application['user_id'] = Input::get('user_id');
        $entity_id = Input::get('entity_id');
        $entity = Entity::find($entity_id);
        return $this->format($this->applySweepstakes($entity, $application));
    }

    /**
     * Get a list of contests matching a name query.
     *
     * @return mixed Formatted array of contests.
     */
    public function apiGetByName()
    {
        $name = Input::get('name', '');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getByName($name, $page, $limit));
    }

}

/**
 * Handle the Contest creation event.
 *
 * @param SOE\DB\Contest $contest
 * @return void
 */
SOE\DB\Contest::created(function($contest)
{
    $contest_id = $contest->id;
    Queue::push(function($job) use ($contest_id)
    {
        $contest = Contest::find($contest_id);
        $contest->createEntity();
        $job->delete();
    });
});

/**
 * Handle the Contest updated event.
 *
 * @param SOE\DB\Contest $contest
 * @return void
 */
SOE\DB\Contest::updated(function($contest)
{
    $contest_id = $contest->id;
    Queue::push(function($job) use ($contest_id)
    {
        $contest = Contest::find($contest_id);
        $contest->updateEntity();
        $job->delete();
    });
});

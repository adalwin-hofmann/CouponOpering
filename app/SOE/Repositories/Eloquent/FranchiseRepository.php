<?php namespace SOE\Repositories\Eloquent;

class FranchiseRepository extends BaseRepository implements \FranchiseRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'company_id',
        'merchant_id',
        'maghub_id',
        'is_active',
        'max_prints',
        'mobile_redemption',
        'primary_contact',
        'is_demo',
        'is_certified',
        'certified_at',
        'uncertified_at',
        'service_plan',
        'zipcode',
        'radius',
        'monthly_budget',
        'contact_phone',
        'project_tags',
        'netlms_id',
        'is_sohi_trial',
        'sohi_trial_starts_at',
        'sohi_trial_ends_at',
        'is_dealer',
        'is_new_car_leads',
        'is_used_car_leads',
        'is_print_only',
        'new_inventory_provider_number',
        'used_inventory_provider_number',
        'lead_email',
        'allow_generic_leads',
        'allow_directed_leads',
        'trial_starts_at',
        'trial_ends_at',
        'trial_lead_cap',
        'can_syndicate',
        'syndication_rating',
        'sponsor_level',
        'sponsor_banner',
        'is_leads_confirmed',
        'banner_package',
        'click_pay_rate',
        'impression_pay_rate',
        'is_offer_notifications',
        'last_offer_notification',
        'contract_start',
        'contract_end',
        'is_permanent',
        'magazinemanager_id'
    );

    protected $model = 'Franchise';
    protected $featureRepository;
    protected $leadEmailRepository;
    protected $merchantRepository;
    protected $projectTagRepository;
    protected $vehicleMakeRepository;
    protected $zipcodeRepository;

    function __construct(
        \DistrictRepositoryInterface $districts,
        \FeatureRepositoryInterface $featureRepository,
        \LeadEmailRepositoryInterface $leadEmailRepository,
        \MerchantRepositoryInterface $merchantRepository,
        \ProjectTagRepositoryInterface $projectTagRepository,
        \UserRepositoryInterface $users,
        \UserFavoriteRepositoryInterface $userFavorites,
        \VehicleMakeRepositoryInterface $vehicleMakeRepository,
        \ZipcodeRepositoryInterface $zipcodeRepository
    )
    {
        $this->districts = $districts;
        $this->featureRepository = $featureRepository;
        $this->leadEmailRepository = $leadEmailRepository;
        $this->merchantRepository = $merchantRepository;
        $this->projectTagRepository = $projectTagRepository;
        $this->users = $users;
        $this->userFavorites = $userFavorites;
        $this->vehicleMakeRepository = $vehicleMakeRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        parent::__construct();
    }

    /**
     * Update the lead emails for the given franchise.
     *
     * @param SOE\DB\Franchise
     * @param mixed     $lead_emails A comma seperated string or array of emails.
     * @return void
     */
    public function updateLeadEmails(\SOE\DB\Franchise $franchise, $lead_emails)
    {
        if(!is_array($lead_emails))
        {
            $lead_emails = explode(',', trim($lead_emails));
        }

        $existing = $this->leadEmailRepository->getByFranchise($franchise);
        $aAdded = array();
        $aRemoved = array();
        $aExisting = array();
        foreach($existing as $email)
        {
            $aExisting[] = $email->email_address.';'.$email->format;
            if(!in_array(($email->email_address.';'.$email->format), $lead_emails))
                $aRemoved[] = array('email' => $email->email_address, 'format' => $email->format);
        }

        foreach($lead_emails as $email)
        {
            if(!in_array($email, $aExisting))
            {
                $pieces = explode(';', $email);
                $aAdded[] = array('email' => $pieces[0], 'format' => (count($pieces) > 1 ? $pieces[1] : 'pretty'));
            }
        }

        if(count($aRemoved))
            $this->leadEmailRepository->removeEmails($franchise, $aRemoved);
        if(count($aAdded))
            $this->leadEmailRepository->addEmails($franchise, $aAdded);
        if(count($aRemoved) || count($aAdded))
            $this->netlmsSync($franchise);
    }

    public function getSponsorBanner($district_slug, $level = null)
    {
        $district = $this->districts->findBySlug($district_slug);
        $query = $this->query()
            ->join('franchise_districts', 'franchise_districts.franchise_id', '=', 'franchises.id')
            ->where('franchises.is_active', '=', '1')
            ->where('franchises.sponsor_banner', '!=', '')
            ->whereNotNull('franchises.sponsor_banner')
            ->where('franchise_districts.franchise_id', $district ? $district->id : 0)
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->join('categories as cat', 'cat.id', '=', 'merchants.category_id')
            ->join('categories as subcat', 'subcat.id', '=', 'merchants.subcategory_id');
        if($level)
        {
            $query->where('franchises.sponsor_level', $level);
        }
        $showDemo = \Auth::check() ? $this->users->showDemo(\Auth::User()) : false;
        if($showDemo == false)
        {
            $query = $query->where('franchises.is_demo', '=', '0');
        }
        $banner = $query->orderBy(\DB::raw('RAND()'))
            ->first(array('merchants.*', 'franchises.sponsor_banner', \DB::raw('cat.slug as cat_slug'), \DB::raw('subcat.slug as subcat_slug')));

        return $banner;
    }

    /**
     * Get all the merchants that can be linked to a dealer.
     *
     * @return mixed
     */
    public function getLinkable()
    {
        return $this->query()
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
            ->where('merchants.vendor', 'soct')
            ->where('merchants.is_active', '1')
            ->where('franchises.is_dealer', '1')
            ->orderBy('merchants.name')
            ->get(array(
                'franchises.id',
                'merchants.name',
                'locations.address'
            ));
    }

    /**
     * Change a franchise's certification.
     *
     * @param SOE\DB\Franchise
     * @param boolean   $is_certified
     * @return SOE\DB\Franchise
     */
    public function setCertification(\SOE\DB\Franchise $franchise, $is_certified)
    {
        if($franchise->is_certified == $is_certified)
            return $franchise;
        $franchise->is_certified = $is_certified;
        if($is_certified)
        {
            $franchise->certified_at = date('Y-m-d H:i:s');
            $franchise->uncertified_at = null;
            \DB::table('franchises')->join('locations', 'franchises.id', '=', 'locations.franchise_id')
                            ->join('entities', 'locations.id', '=', 'entities.location_id')
                            ->where('franchises.id', '=', $franchise->id)
                            ->update(array('entities.is_certified' => '1'));

            $contact = $franchise->primary_contact;
            \Mail::send('emails.certified-email', [], function($message) use ($contact)
            {
                $message->to($contact, '')->subject('SaveOn - SAVE Certified!');
            });
        }
        else
        {
            if(empty($franchise->certified_at))
                return $franchise;
            $franchise->uncertified_at = date('Y-m-d H:i:s');
            $lead_emails = $this->leadEmailRepository->getByFranchise($franchise);
            $aEmails = array();
            foreach($lead_emails as $email)
            {
                $aEmails[] = $email->email_address;
            }
            $this->leadEmailRepository->removeEmails($franchise, $aEmails);
            \DB::table('franchises')->join('locations', 'franchises.id', '=', 'franchise_id')
                            ->join('entities', 'locations.id', '=', 'entities.location_id')
                            ->where('franchises.id', '=', $franchise->id)
                            ->update(array('entities.is_certified' => '0'));
        }
        return $franchise;
    }

    /**
     * Get the merchant associated with this franchise.
     *
     * @param SOE\DB\Franchise
     * @return SOE\DB\Merchant
     */
    public function getMerchant(\SOE\DB\Franchise $franchise)
    {
        return $this->merchantRepository->find($franchise->merchant_id);
    }

    public function searchTypeahead($query)
    {
        $query = str_replace(array("'", "."), "", $query);
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $radius = $this->featureRepository->findByName('search_dist');
        $radius = empty($radius) ? 60000 : $radius->value;
        $minLatm = floor(abs($cartesian['latm'])-$radius);
        $maxLatm = floor(abs($cartesian['latm'])+$radius);
        $minLngm = floor(abs($cartesian['lngm'])-$radius);
        $maxLngm = floor(abs($cartesian['lngm'])+$radius);
        $sql = <<<SQL
SELECT st.search 
    FROM 
    (
        select REPLACE(REPLACE(lcase(merchants.name), '\'', ''), '.', '') as search, count(*) as cnt 
        from merchants 
        inner join locations on merchants.id = locations.merchant_id
        where merchants.name like ? 
            and merchants.is_active = 1
            and merchants.is_displayed = 1
            and merchants.deleted_at IS NULL
            and merchants.is_demo != ?
            and locations.latm > ?
            and locations.latm < ?
            and abs(locations.lngm) > ?
            and abs(locations.lngm) < ?
        group by merchants.name order by cnt desc 
        limit 0,5
    ) as st
    UNION
    SELECT m.search
    FROM
    (
        select REPLACE(REPLACE(lcase(search_text), '\'', ''), '.', '') as search,count(*) as cnt 
        from user_searches 
        where search_text like ? 
            and state = ? 
            and results > 0
        group by search_text 
        order by cnt desc
        limit 0,3
    ) as m
SQL;
        $demo = \SoeHelper::isTrackable() ? 1 : -1;
        return \DB::select($sql, [$query.'%', $demo, $minLatm, $maxLatm, $minLngm, $maxLngm, $query.'%', $geoip->region_name]);
    }

    /**
     * Get a random featured dealer near the given latitude, longitude.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return mixed
     */
    public function getFeaturedDealer($latitude, $longitude, $dealers_only = false)
    {
        $radius = $this->featureRepository->findByName('recommendation_dist');
        $radius = empty($radius) ? 34000 : $radius->value;
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $franchise = $this->with('merchant.eagerAssets', 'merchant')
                        ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
                        ->where('franchises.is_featured', '1')
                        ->where(\DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))'), '<', $radius);
        if($dealers_only)
            $franchise->where('is_dealer', '1');
        $franchise = $franchise->orderBy(\DB::raw('RAND()'))
                        ->first(array('franchises.*', 'locations.*'));
        $return = array('objects' => array());
        if($franchise)
            $return['objects'][] = $franchise;
        return $return;
    }

    /**
     * Get all featured dealers near the given latitude, longitude.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return mixed
     */
    public function getFeaturedDealers($latitude, $longitude, $page = 0, $limit = 0)
    {
        $radius = $this->featureRepository->findByName('recommendation_dist');
        $radius = empty($radius) ? 34000 : $radius->value;
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = $this->with('merchant.eagerAssets', 'merchant')
                        ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
                        ->where('franchises.is_featured', '1')
                        ->where(\DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))'), '<', $radius)
                        ->orderBy($distance);
        $query->groupBy('franchises.id');
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $stats = $this->getStats(clone $query, $limit, $page);
        $results = $query->get(array('franchises.*', 'locations.*'));
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    /**
     * Set the project tags associated with the given franchise.
     *
     * @param SOE\DB\Franchise $franchise
     * @param array $aTags
     * @return void
     */
    public function setProjectTags(\SOE\DB\Franchise $franchise, $aTags = array())
    {
        $existing = $this->projectTagRepository->getFranchiseTags($franchise);
        $aExisting = array();
        foreach($existing as $exist)
        {
            $aExisting[] = $exist->id;
        }
        $missing = array_diff($aExisting, $aTags);
        $missing[] = 0;
        \DB::table('franchise_project_tag')->where('franchise_id', '=', $franchise->id)->whereIn('project_tag_id', $missing)->delete();
        $new = array_diff($aTags, $aExisting);
        foreach($new as $n)
        {
            \DB::table('franchise_project_tag')->insert(array('franchise_id' => $franchise->id, 'project_tag_id' => $n));
        }
        if(count($missing) > 1 || count($new))
            $this->netlmsSync($franchise);
        // Update the entities
        $sTags = implode(',', $aTags);
        $locations = \SOE\DB\Location::where('franchise_id', '=', $franchise->id)->get(array('id'));
        $aLocIDs = array(0);
        foreach($locations as $location)
        {
            $aLocIDs[] = $location->id;
        }
        \SOE\DB\Entity::where('merchant_id', '=', $franchise->merchant_id)->whereIn('location_id', $aLocIDs)->update(array('project_tags' => $sTags));
    }

    /**
     * Create this franchise in netlms.
     *
     * @param SOE\DB\Franchise $franchise
     * @return void
     */
    public function netlmsCreate(\SOE\DB\Franchise $franchise, $trial = false)
    {
        $api = \App::make('NetLMSAPIInterface');
        $merchant = $this->merchantRepository->find($franchise->merchant_id);
        $emails = $this->leadEmailRepository->getByFranchise($franchise);
        $zipcode = $this->zipcodeRepository->findByZipcode($franchise->zipcode);
        if(empty($merchant))
            return;
        $zipcodes = empty($zipcode) ? array() : $this->zipcodeRepository->getByRadius($zipcode->latitude, $zipcode->longitude, $franchise->radius);
        $aEmails = array();
        foreach($emails as $email)
        {
            $aEmails[] = array('email' => $email->email_address, 'format' => $email->format);
        }
        $aZips = array();
        foreach($zipcodes as $zip)
        {
            $aZips[] = $zip->zipcode;
        }
        $data = array(
            'type' => ($franchise->is_dealer == 1 ? 'automotive' : 'home-improvement'),
            'name' => $merchant->name,
            'phone' => $franchise->contact_phone ? $franchise->contact_phone : '',
            //'email' => $franchise->primary_contact,
            'tier' => $franchise->service_plan,
            'allow_directed' => $franchise->allow_directed_leads ? $franchise->allow_directed_leads : 0,
            'allow_generic' => $franchise->allow_generic_leads ? $franchise->allow_generic_leads : 0,
            'vendor' => $merchant->vendor ? $merchant->vendor : '',
            'vendor_customer_id' => $merchant->old_id
        );
        if($trial)
        {
            $trial_start = $this->featureRepository->findByName('sohi_free_trial_start', false);
            $trial_start = $trial_start ? $trial_start->value : '2014-04-23 00:00:00';
            $trial_end = $this->featureRepository->findByName('sohi_free_trial_end', false);
            $trial_end = $trial_end ? $trial_end->value : '2014-06-30 00:00:00';
            $data['trial_begin'] = $trial_start;
            $data['trial_end'] = $trial_end;
        }
        if($franchise->service_plan == 'trial')
        {
            $data['trial_begin'] = $franchise->trial_starts_at;
            $data['trial_end'] = $franchise->trial_ends_at;
            $data['trial_cap'] = $franchise->trial_lead_cap;
        }
        //$data = json_encode($data);
        $response = $api->curl('POST', 'customer', null, $data);
        if($response['status'] != 200)
        {
            \DB::table('sys_logs')->insert(array(
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'type' => 'netlms_customer_creation_failure',
                'message' => 'FranchiseID:'.$franchise->id.' - '.json_encode($response['response'])
            ));
            return;
        }
        $oResponse = $response['response'];
        $franchise->netlms_id = $oResponse->id;

        // Update the account
        $account_id = $oResponse->accounts[0]->id;
        $data = array(
            'budget' => $franchise->monthly_budget ? $franchise->monthly_budget : 0
        );
        $api->curl('PUT', 'account', $account_id, $data);
        // Create the contacts
        foreach($aEmails as $email)
        {
            $data = array(
                'account' => $account_id,
                'type' => 'lead',
                'method' => 'email',
                'endpoint' => $email['email'],
                'format' => $email['format']
            );
            $api->curl('POST', 'contact', null, $data);
        }
        // Create the orders for SOHI franchises
        $tags = array();
        if(!$franchise->is_dealer)
        {
            $tags = $this->projectTagRepository->getFranchiseTags($franchise);
            foreach($tags as $tag)
            {
                $data = array(
                    'account' => $account_id,
                    'category' => $tag->slug,
                    'service_zips' => array()
                );
                foreach($aZips as $zip)
                {
                    $data['service_zips'][] = $zip;
                }
                $api->curl('POST', 'order', null, $data);
            }
        }
        
        $franchise->save();
    }

    /**
     * Update this franchise in netlms.
     *
     * @param SOE\DB\Franchise $franchise
     * @return void
     */
    public function netlmsSync(\SOE\DB\Franchise $franchise)
    {
        if(!$franchise->netlms_id)
            return;
        $api = \App::make('NetLMSAPIInterface');
        // If Franchise is uncertified or is no longer a dealer, delete it from NETLMS
        if($franchise->uncertified_at != null || (!$franchise->is_certified && $franchise->uncertified_at == null && !$franchise->is_dealer))
        {
            $api->curl('DELETE', 'customer', $franchise->netlms_id);
            $franchise->netlms_id = \DB::raw('NULL');
            $franchise->save();
        }
        else
        {
            $merchant = $this->merchantRepository->find($franchise->merchant_id);
            $emails = $this->leadEmailRepository->getByFranchise($franchise);
            $zipcode = $this->zipcodeRepository->findByZipcode($franchise->zipcode);
            if(empty($merchant))
                return;
            $zipcodes = empty($zipcode) ? array() : $this->zipcodeRepository->getByRadius($zipcode->latitude, $zipcode->longitude, $franchise->radius);
            $aEmails = array();
            foreach($emails as $email)
            {
                $aEmails[] = $email->email_address.';'.$email->format;
            }
            $aZips = array();
            foreach($zipcodes as $zip)
            {
                $aZips[] = $zip->zipcode;
            }
            $data = array(
                'name' => $merchant->name,
                'phone' => $franchise->contact_phone ? $franchise->contact_phone : '',
                //'email' => $franchise->primary_contact,
                'tier' => $franchise->service_plan,
                'allow_directed' => $franchise->allow_directed_leads ? $franchise->allow_directed_leads : 0,
                'allow_generic' => $franchise->allow_generic_leads ? $franchise->allow_generic_leads : 0,
                'vendor' => $merchant->vendor ? $merchant->vendor : '',
                'vendor_customer_id' => $merchant->old_id
            );
            if($franchise->service_plan == 'trial')
            {
                $data['trial_begin'] = $franchise->trial_starts_at ? $franchise->trial_starts_at : $franchise->sohi_trial_starts_at;
                $data['trial_end'] = $franchise->trial_ends_at ? $franchise->trial_ends_at : $franchise->sohi_trial_ends_at;
                $data['trial_cap'] = $franchise->trial_lead_cap;
            }
            else
            {
                $data['trial_begin'] = NULL;
                $data['trial_end'] = NULL;
                $data['trial_cap'] = 0;
            }
            //$data = json_encode($data);
            $response = $api->curl('PUT', 'customer', $franchise->netlms_id, $data);
            if($response['status'] != 200)
                return;
            $oResponse = $response['response'];
            // Update the account
            $account_id = $oResponse->accounts[0]->id;
            $data = array(
                'budget' => $franchise->monthly_budget ? $franchise->monthly_budget : 0
            );
            $api->curl('PUT', 'account', $account_id, $data);
            // Update the contacts
            $aExisting = array();
            foreach($oResponse->accounts[0]->contacts as $contact)
            {
                // Remove contacts that no longer exist
                if(!in_array($contact->endpoint.';'.$contact->format, $aEmails))
                    $api->curl('DELETE', 'contact', $contact->id);
                else
                    $aExisting[] = $contact->endpoint.';'.$contact->format;
            }
            foreach($aEmails as $email)
            {
                if(!in_array($email, $aExisting))
                {
                    $pieces = explode(';', $email);
                    // Create new contacts
                    $data = array(
                        'account' => $account_id,
                        'type' => 'lead',
                        'method' => 'email',
                        'endpoint' => $pieces[0],
                        'format' => count($pieces) > 1 ? $pieces[1] : 'pretty'
                    );
                    $api->curl('POST', 'contact', null, $data);
                }
            }
            // Update the orders for SOHI franchises
            $aTags = array();
            $tags = array();
            if(!$franchise->is_dealer)
            {
                $tags = $this->projectTagRepository->getFranchiseTags($franchise);
                foreach($tags as $tag)
                {
                    $aTags[] = $tag->slug;
                }

                $aExisting = array();
                foreach($oResponse->accounts[0]->orders as $order)
                {
                    // Remove orders that no longer exist
                    if(!in_array($order->category, $aTags))
                        $api->curl('DELETE', 'order', $order->id);
                    else
                        $aExisting[$order->category] = $order->id;
                }
                foreach($tags as $tag)
                {
                    if(!isset($aExisting[$tag->slug]))
                    {
                        // Create new orders
                        $data = array(
                            'account' => $account_id,
                            'category' => $tag->slug,
                            'service_zips' => array()
                        );
                        foreach($aZips as $zip)
                        {
                            $data['service_zips'][] = $zip;
                        }
                        $api->curl('POST', 'order', null, $data);
                    }
                    else
                    {
                        // Update existing orders
                        $data = array(
                            'service_zips' => array()
                        );
                        foreach($aZips as $zip)
                        {
                            $data['service_zips'][] = $zip;
                        }
                        $api->curl('PUT', 'order', $aExisting[$tag->slug], $data);
                    }
                }
            }
        }
    }

    /**
     * Get all franchises assigned to the given sales rep.
     *
     * @param SOE\DB\User $user
     * @return array
     */
    public function getByRep(\SOE\DB\User $user)
    {
        return \SOE\DB\Franchise::join('franchise_assignments', 'franchises.id', '=', 'franchise_assignments.franchise_id')
                                ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                                ->where('franchise_assignments.user_id', '=', $user->id)
                                ->where('franchises.is_active', '=', '1')
                                ->orderBy('merchants.display')
                                ->get(array(
                                    'franchises.*',
                                    \DB::raw('merchants.display as merchant_name'),
                                    \DB::raw('merchants.about as merchant_about'),
                                    \DB::raw('merchants.page_title as merchant_title'),
                                    \DB::raw('merchants.meta_description as merchant_description')
                                ));
    }

    public function getSalesRep($franchise_id)
    {
        /*return \SOE\DB\User::join('franchise_assignments', 'users.id', '=', 'franchise_assignments.user_id')
                            ->where('franchises_assignments.franchise_id', '=', $franchise_id)
                            ->first();*/
        return $this->find($franchise_id)->assignments()->where('franchise_assignments.assignment_type_id', '=', '1')->first();
    }

    public function withMerchants($franchises)
    {
        $aMerchIDs = array(0);
        foreach($franchises as $franchise)
        {
            $aMerchIDs[] = $franchise->merchant_id;
        }
        $merchants = \SOE\DB\Merchant::whereIn('id', $aMerchIDs)->get();
        $aMerch = array();
        foreach($merchants as $merch)
        {
            $aMerch[$merch->id] = $merch;
        }
        foreach($franchises as &$franchise)
        {
            $franchise->merchant = isset($aMerch[$franchise->merchant_id]) ? $aMerch[$franchise->merchant_id] : array();
        }
        return $franchises;
    }

    private function createSalesReportBuilder($user_id)
    {
        $builder = \SOE\DB\Franchise::select()
            ->from('assignment_types')
            ->byAssignmentType('Sales Person')
            ->active()
            ->merchants()
            ->groupBy('franchises.id')
            ->orderBy('users.id');

        if ($user_id) {
            $builder->where('users.id', '=', $user_id);
        }

        return $builder;

    }

    private function findById($needle, $haystack)
    {
        if (array_key_exists($needle, $haystack)) {
            return $haystack[$needle];
        } else {
            return 0;
        }
    }

    private function createQuickRef($collection, $value)
    {
        $array = array();
        foreach($collection as $item) {
            $array[$item->getKey()] = $item->$value;
        }
        return $array;
    }

    public function getSalesReport($user_id = false)
    {
        $select = array(
            'users.id as user_id',
            'users.name as user_name',
            'franchises.id',
            'merchants.id as merchant_id',
            'merchants.name as merchant_name',
            'merchants.about as merchant_about',
        );

        // Each one of these *must* be separate
        $all_offers = $this->createSalesReportBuilder($user_id)
            ->locations()
            ->aggregateAllOffers(array_merge($select, array('locations.state')))->get();

        // Collections of franchises that have:
        //      active offers, expiring offers, and contest applicants
        $active_offers = $this->createSalesReportBuilder($user_id)
            ->aggregateActiveOffers($select)->get();

        $expiring_offers = $this->createSalesReportBuilder($user_id)
            ->aggregateExpiringOffers($select)->get();

        $applicants = $this->createSalesReportBuilder($user_id)
            ->aggregateContests($select)->get();

        // Aggregate all the data into a franchises object
        $franchises = $all_offers;

        // re-store the values we need for quick reference
        // Collection::find() gets too slow with a lot of results
        $active_offers_ref = $this->createQuickRef($active_offers, 'active_offers');
        $exp_offers_ref = $this->createQuickRef($expiring_offers, 'expiring_offers');
        $applicants_ref = $this->createQuickRef($applicants, 'applicants');

        // Generate lookup table
        $franchise_lookup = array();
        foreach ($franchises as $id => $franchise) {
            $franchise_lookup[$franchise->id] = $id;
        }

        foreach ($franchises as &$franchise) {
            $franchise->active_offers = $this->findById($franchise->id, $active_offers_ref);
            $franchise->expiring_offers = $this->findById($franchise->id, $exp_offers_ref);
            $franchise->applicants = $this->findById($franchise->id, $applicants_ref);
        }

        return $franchises;
    }

    /**
     * Retrieve franchises of the given type that are able to receive leads.
     *
     * @param string $type
     * @return array
     */
    public function getsLeads($type = null)
    {
        $query = $this->query()->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                            ->where('franchises.is_active', '1')
                            ->where('franchises.is_demo', '0')
                            ->whereNotNull('netlms_id')
                            ->orderBy('merchants.name');
        if($type)
        {
            $query->where('is_certified', ($type == 'sohi' ? 1 : 0));
        }
        return $query->get(array('franchises.*'));
    }

    /**
     * Retrieve the notes for the given franchise.
     *
     * @param int $franchise_id
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getNotes($franchise_id, $page = 0, $limit = 0)
    {
        $franchise = $this->find($franchise_id);
        if(!$franchise)
            return array('objects' => array(), 'stats' => array('page' => 0, 'take' => 0, 'total' => 0, 'returned' => 0));
        $notes = $franchise->notes();
        $stats = $this->getStats(clone $notes, $limit, $page);
        $notes = $notes->setPagination($page, $limit)->get();
        $stats['stats']['returned'] = count($notes);
        return array_merge(array('objects' => $notes), $stats);
    }

    /**
     * Get a list of all merchants with active coupons by state, by subcategory
     *
     * @return array
     */
    public function getCategoryActiveReport()
    {
        $merchants = $this->query()->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                                ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
                                ->join('offers', 'franchises.id', '=', 'offers.franchise_id')
                                ->join(\DB::raw('categories as cat'), 'merchants.category_id', '=', 'cat.id')
                                ->join(\DB::raw('categories as subcat'), 'merchants.subcategory_id', '=', 'subcat.id')
                                ->where('franchises.is_active', '1')
                                ->where('franchises.is_demo', '0')
                                ->where('locations.is_active', '1')
                                ->where('locations.is_demo', '0')
                                ->where('merchants.yipitbusiness_id', '0')
                                ->where('merchants.type', '!=', 'PROSPECT')
                                ->where('offers.is_active', '1')
                                ->where('offers.is_demo', '0')
                                ->where('offers.expires_at', '>', \DB::raw('NOW()'))
                                ->where('offers.starts_at', '<', \DB::raw('NOW()'))
                                ->groupBy('franchises.id')
                                ->groupBy('locations.state')
                                ->orderBy('locations.state')
                                ->orderBy('category_name')
                                ->orderBy('subcategory_name')
                                ->orderBy('merchants.name')
                                ->get(array(
                                    'merchants.display', 
                                    'locations.state', 
                                    \DB::raw('cat.name as category_name'), 
                                    \DB::raw('subcat.name as subcategory_name')
                                ));
        $aStates = array();
        foreach($merchants as $merchant)
        {
            if(!isset($aStates[$merchant->state]))
            {
                $aStates[$merchant->state] = array(
                    'categories' => array(
                        $merchant->category_name => array(
                            'subcategories' => array(
                                $merchant->subcategory_name => array(
                                    'merchants' => array(
                                        $merchant->toArray()
                                    ), 
                                    'count' => 1
                                )
                            ), 
                            'count' => 1
                        )
                    ),
                    'count' => 1
                );
            }
            else
            {
                if(!isset($aStates[$merchant->state]['categories'][$merchant->category_name]))
                {
                    $aStates[$merchant->state]['categories'][$merchant->category_name] = array(
                        'subcategories' => array(
                            $merchant->subcategory_name => array(
                                'merchants' => array(
                                    $merchant->toArray()
                                ), 
                                'count' => 1
                            )
                        ),
                        'count' => 1
                    );
                    $aStates[$merchant->state]['count']++;
                }
                else
                {
                    if(!isset($aStates[$merchant->state]['categories'][$merchant->category_name]['subcategories'][$merchant->subcategory_name]))
                    {
                        $aStates[$merchant->state]['categories'][$merchant->category_name]['subcategories'][$merchant->subcategory_name] = array(
                            'merchants' => array(
                                $merchant->toArray()
                            ), 
                            'count' => 1
                        );
                        $aStates[$merchant->state]['count']++;
                        $aStates[$merchant->state]['categories'][$merchant->category_name]['count']++;
                    }
                    else
                    {
                        $aStates[$merchant->state]['categories'][$merchant->category_name]['subcategories'][$merchant->subcategory_name]['merchants'][] = $merchant->toArray();
                        $aStates[$merchant->state]['count']++;
                        $aStates[$merchant->state]['categories'][$merchant->category_name]['count']++;
                        $aStates[$merchant->state]['categories'][$merchant->category_name]['subcategories'][$merchant->subcategory_name]['count']++;
                    }
                }
            }
        }

        return array('objects' => $aStates);
    }

    /**
     * Find a franchise by company_id and merchant_id.
     *
     * @param int $company_id
     * @param int $merchant_id
     * @return mixed
     */
    public function findByCompanyMerchant($company_id, $merchant_id)
    {
        return $this->query()->where('company_id', $company_id)
                            ->where('merchant_id', $merchant_id)
                            ->first();
    }

    public function updateLeadsPurchased()
    {
        $api = \App::make('NetLMSAPIInterface');
        $response = $api->curl(
            'GET', 
            'report', 
            null, 
            '', 
            'all-purchased-leads'
        );

        if($response['status'] != 200)
        {
            return;
        }

        $data = array();
        foreach($response['response'] as $lead)
        {
            $data[] = array(
                'netlms_id' => $lead->customer_key,
                'netlms_lead_id' => $lead->lead_id,
                'purchased_at' => $lead->created_at,
                'name' => $lead->first.' '.$lead->last,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'price' => $lead->selling_price
            );
        }
        if(count($data))
        {
            \DB::table('lead_purchases')->truncate();
            \DB::table('lead_purchases')->insert($data);
            \DB::table('lead_purchases')->join('franchises', 'lead_purchases.netlms_id', '=', 'franchises.netlms_id')
                ->update(array(
                    'lead_purchases.franchise_id' => \DB::raw('`franchises`.`id`')
                ));
        }
    }

    public function searchReport(
        $filter = null, $category = null, $subcategory = null,
        $sales_rep = null, $market = null, $start = null,
        $end = null, $page = 0, $limit = 12,
        $order = 'name', $dir = 'asc'
    )
    {
        if($start)
        {
            $start = date('Y-m-d', strtotime($start));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }
        if($end)
        {
            $end = date('Y-m-d', strtotime($end));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }

        $query = \SOE\DB\Franchise::join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->where('franchises.is_active', '1')
            ->where('franchises.is_demo', '0')
            ->where('merchants.yipitbusiness_id', '0')
            ->where('merchants.is_active', '1')
            ->where('merchants.is_demo', '0');
        if($filter)
        {
            $query->where(function($query) use ($filter)
            {
                $query->where('merchants.name', 'LIKE', '%'.$filter.'%');
                $query->orWhere('merchants.display', 'LIKE', '%'.$filter.'%');
                $query->orWhere('franchises.name', 'LIKE', '%'.$filter.'%');
            });
        }
        if($category)
            $query->where('merchants.category_id', $category);
        if($subcategory)
            $query->where('merchants.subcategory_id', $subcategory);
        if($sales_rep)
        {
            $sales = \SOE\DB\AssignmentType::where('name', 'Sales Person')->first();
            $query->join('franchise_assignments', 'franchises.id', '=', 'franchise_assignments.franchise_id')
                ->where('franchise_assignments.assignment_type_id', '=', $sales->id)
                ->where('franchise_assignments.user_id', $sales_rep);
        }
        
        if($market)
        {
            $query->join('locations', 'franchises.id', '=', 'locations.franchise_id');
            $query->where('locations.state', strtoupper($market));
        }

        $stats = $this->getStats(clone $query, $limit, $page, true);
        // Get Contest Applicants 
        $query->leftJoin('contests', 'franchises.id', '=', 'contests.franchise_id')
            ->leftJoin(\DB::raw(
            "(select contest_id, count(*) as applicants from (
                select contest_id, count(*) from contest_applications
                    where created_at  > '".date('Y-m-d 00:00:00', strtotime($start))."'
                        and created_at < '".date('Y-m-d 23:59:59', strtotime($end))."'
                    group by contest_id, user_id) contest_apps
                group by contest_apps.contest_id) total_apps"), 'contests.id', '=', 'total_apps.contest_id');
        $query->addSelect(\DB::raw("SUM(total_apps.applicants) as apps"));

        $query->leftJoin(\DB::raw(
            "(select total.franchise_id, SUM(total.quotes) as quotes from 
                (select franchises.id as franchise_id, IF(lead_purchases.id IS NULL, 'home', 'auto') as quote_type, count(*) as quotes from franchises
                    left join lead_purchases on franchises.id = lead_purchases.franchise_id
                    left join quotes on franchises.id = quotes.franchise_id
                    where (lead_purchases.id IS NOT NULL
                        or quotes.id IS NOT NULL)
                        and (
                                (lead_purchases.purchased_at  > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and lead_purchases.purchased_at < '".date('Y-m-d 23:59:59', strtotime($end))."')
                            or
                                (quotes.created_at  > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and quotes.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."')
                            )
                    group by franchises.id, quote_type) as total
                group by total.franchise_id) as total_quotes"), 'franchises.id', '=', 'total_quotes.franchise_id');
        $query->addSelect('total_quotes.quotes');

        $query->groupBy('franchises.id');
        switch ($order)
        {
            case 'views':
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from user_views
                            where user_views.franchise_id = franchises.id
                                and user_views.created_at > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and user_views.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."') as views")))
                    ->orderBy('views', $dir);
                break;
            case 'prints':
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from user_prints
                            where user_prints.merchant_id = franchises.merchant_id
                                and user_prints.created_at > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and user_prints.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."') as prints")))
                    ->orderBy('prints', $dir);
                break;
            case 'offers':
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from offers
                            where offers.franchise_id = franchises.id
                                and offers.is_active = 1
                                and offers.is_demo = 0
                                and offers.expires_at > '".date('Y-m-d 00:00:00')."') as offers")))
                    ->orderBy('offers', $dir);
                break;
            case 'applications':
                $query->orderBy('apps', $dir);
                break;
            case 'quotes':
                $query->orderBy('quotes', $dir);
                break;
            default:
                $query->orderBy('merchants.name', $dir);
                break;
        }
        
        if($limit)
            $query->take($limit)->skip($page*$limit);
        $franchises = $query->addSelect(array(
            \DB::raw('franchises.id as franchise_id'),
            \DB::raw('merchants.id as merchant_id'),
            \DB::raw('merchants.display as merchant_display')
        ))->remember(60*12)->get();

        foreach($franchises as &$franchise)
        {
            $api_key = \Config::get('integrations.mixpanel.key');
            $api_secret = \Config::get('integrations.mixpanel.secret');
            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => $franchise->merchant_id.' == properties["MerchantId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $views = 0;
            foreach($data->data->values->{'Location View'} as $key => $value)
            {
                $views += $value;
            }
            $franchise->views = $views;

            $prints = \SOE\DB\UserPrint::join('locations', 'user_prints.location_id', '=', 'locations.id')
                ->join('franchises', 'locations.franchise_id', '=', 'franchises.id')
                ->where('franchises.id', $franchise->franchise_id)
                ->where('user_prints.created_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))
                ->where('user_prints.created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)))
                ->remember(60*12)
                ->count();
            $franchise->prints = $prints;

            $offers = \SOE\DB\Offer::where('franchise_id', $franchise->franchise_id)
                ->where('offers.is_active', '1')
                ->where('offers.is_demo', '0')
                ->where('expires_at', '>', date('Y-m-d 00:00:00'))
                ->remember(60*12)
                ->count();
            $franchise->offers = $offers;

            /*$applicants = \SOE\DB\Contest::where('franchise_id', $franchise->id)
                ->join('contest_applications', 'contests.id', '=', 'contest_applications.contest_id')
                ->where('contest_applications.created_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))
                ->where('contest_applications.created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)))
                ->groupBy('contest_applications.contest_id')
                ->groupBy('contest_applications.user_id')
                ->count();
            $franchise->total_apps = count($applicants);*/
        }

        $return = array('objects' => $franchises);
        $stats['stats']['returned'] = count($franchises);
        return array_merge($return, $stats);
    }

    public function categorySearchReport(
        $category = null, $sales_rep = null, $market = null,
        $start = null, $end = null, $page = 0,
        $limit = 12, $order = 'name', $dir = 'asc'
    )
    {
        if($start)
        {
            $start = date('Y-m-d', strtotime($start));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }
        if($end)
        {
            $end = date('Y-m-d', strtotime($end));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }

        $query = \SOE\DB\Franchise::join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->where('franchises.is_active', '1')
            ->where('franchises.is_demo', '0')
            ->where('merchants.yipitbusiness_id', '0')
            ->where('merchants.is_active', '1')
            ->where('merchants.is_demo', '0')
            ->join(\DB::raw('categories as cat'), 'merchants.category_id', '=', 'cat.id')
            ->join(\DB::raw('categories as subcat'), 'merchants.subcategory_id', '=', 'subcat.id');
        if($category)
        {
            $query->where('merchants.category_id', $category)
                ->groupBy('subcat.id');
        }
        else
        {
            $query->groupBy('cat.id');
        }
        if($sales_rep)
        {
            $sales = \SOE\DB\AssignmentType::where('name', 'Sales Person')->first();
            $query->join('franchise_assignments', 'franchises.id', '=', 'franchise_assignments.franchise_id')
                ->where('franchise_assignments.assignment_type_id', '=', $sales->id)
                ->where('franchise_assignments.user_id', $sales_rep);
        }

        $stats = $this->getStats(clone $query, $limit, $page, true);

        // Get Contest Applicants 
        if($market)
        {
            $raw = "inner join contests on contest_applications.contest_id = contests.id
                    inner join locations on contests.franchise_id = locations.franchise_id
                    inner join merchants on contests.merchant_id = merchants.id ";
            $where = "and locations.state = '".strtoupper($market)."'";
        }
        else
        {
            $raw = "";
            $where = "";
        }
        $query->leftJoin('contests', 'franchises.id', '=', 'contests.franchise_id')
            ->leftJoin(\DB::raw(
            "(select contest_id, count(*) as applicants from (
                select contest_id, count(*) from contest_applications
                    ".$raw."
                    where contest_applications.created_at  > '".date('Y-m-d 00:00:00', strtotime($start))."'
                        ".$where."
                        and contest_applications.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."'
                    group by contest_id, user_id) contest_apps
                group by contest_apps.contest_id) total_apps"), 'contests.id', '=', 'total_apps.contest_id');
        $query->addSelect(\DB::raw("SUM(total_apps.applicants) as apps"));

        //$query->groupBy('franchises.id');
        switch ($order)
        {
            case 'views':
                $where = $category ? 'merchants.subcategory_id = subcat.id' : 'merchants.category_id = cat.id';
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from user_views
                            inner join merchants on user_views.merchant_id = merchants.id
                            where ".$where."
                                and user_views.created_at > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and user_views.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."') as views")))
                    ->orderBy('views', $dir);
                break;
            case 'prints':
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from user_prints
                            where user_prints.merchant_id = franchises.merchant_id
                                and user_prints.created_at > '".date('Y-m-d 00:00:00', strtotime($start))."'
                                and user_prints.created_at < '".date('Y-m-d 23:59:59', strtotime($end))."') as prints")))
                    ->orderBy('prints', $dir);
                break;
            case 'offers':
                $query->addSelect(array(\DB::raw(
                        "(select count(*) from offers
                            where offers.franchise_id = franchises.id
                                and offers.is_active = 1
                                and offers.is_demo = 0
                                and offers.expires_at > '".date('Y-m-d 00:00:00')."') as offers")))
                    ->orderBy('offers', $dir);
                break;
            case 'applications':
                $query->orderBy('apps', $dir);
                break;
            default:
                if($category)
                    $query->orderBy('subcat.name', $dir);
                else
                    $query->orderBy('cat.name');
                break;
        }
        
        if($limit)
            $query->take($limit)->skip($page*$limit);

        $categories = $query->addSelect(array(
            \DB::raw('cat.id as category_id'),
            \DB::raw('subcat.id as subcategory_id'),
            \DB::raw('cat.name as category_name'),
            \DB::raw('subcat.name as subcategory_name')
        ))->remember(60*12)->get();

        foreach($categories as &$cat)
        {
            $api_key = \Config::get('integrations.mixpanel.key');
            $api_secret = \Config::get('integrations.mixpanel.secret');
            $mp = new \MPData($api_key, $api_secret);
            if($category)
                $where = '"'.$cat->subcategory_name.'" == properties["Subcategory"]';
            else
                $where = '"'.$cat->category_name.'" == properties["Category"]';
            if($market)
                $where .= ' and "'.strtoupper($market).'" == properties["$region"]';
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => $where.' and properties["Environment"] == "prod"',
                'type' => 'general',
            ));
            $views = 0;
            foreach($data->data->values->{'Location View'} as $key => $value)
            {
                $views += $value;
            }
            $cat->views = $views;

            $prints = \SOE\DB\UserPrint::join('merchants', 'user_prints.merchant_id', '=', 'merchants.id')
                ->where('user_prints.created_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))
                ->where('user_prints.created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)));
            if($category)
                $prints->where('merchants.subcategory_id', $cat->subcategory_id);
            else
                $prints->where('merchants.category_id', $cat->category_id);
            if($market)
            {
                $prints->join('locations', 'user_prints.location_id', '=', 'locations.id')
                    ->where('locations.state', '=', strtoupper($market));
            }
            $prints = $prints->remember(60*12)
                ->count();
            $cat->prints = $prints;

            $offers = \SOE\DB\Offer::join('merchants', 'offers.merchant_id', '=', 'merchants.id')
                ->where('offers.is_active', '1')
                ->where('offers.is_demo', '0')
                ->where('expires_at', '>', date('Y-m-d 00:00:00'));
            if($category)
                $offers->where('merchants.subcategory_id', $cat->subcategory_id);
            else
                $offers->where('merchants.category_id', $cat->category_id);
            if($market)
            {
                $offers->join('franchises', 'offers.franchise_id', '=', 'franchises.id')
                    ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
                    ->where('locations.state', strtoupper($market))
                    ->groupBy('offers.id');
            }
            $offers = $offers->remember(60*12)
                ->get(array(\DB::raw('1')));
            $cat->offers = count($offers);
        }

        $return = array('objects' => $categories);
        $stats['stats']['returned'] = count($categories);
        return array_merge($return, $stats);
    }

    public function franchiseDashReport($franchise_id, $start, $end, $location_id = 0)
    {
        $start_day = date('Y-m-d', strtotime($start));
        $end_day = date('Y-m-d', strtotime($end));
        $franchise = $this->find($franchise_id);

        $api_key = \Config::get('integrations.mixpanel.key');
        $api_secret = \Config::get('integrations.mixpanel.secret');
        $where = '';
        if($location_id)
            $where = $location_id.' == properties["LocationId"] and ';
        try
        {
            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',
                'where' => $where.$franchise->id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $views = 0;
            foreach($data->data->values->{'Location View'} as $key => $value)
            {
                $views += $value;
            }
            $franchise->current_views = $views;

            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',
                'where' => $where.$franchise->id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $prints = 0;
            foreach($data->data->values->{'Offer Print'} as $key => $value)
            {
                $prints += $value;
            }
            $franchise->current_prints = $prints;

            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'User Share',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',
                'where' => $where.'"'.$franchise_id.'" == properties["FranchiseId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $shares = 0;
            foreach($data->data->values->{'User Share'} as $key => $value)
            {
                $shares += $value;
            }
            $franchise->current_shares = $shares;

            /*$contests = \SOE\DB\Contest::leftJoin(\DB::raw(
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
                ->take($offer_limit)
                ->get(array('total_apps.apps', 'contests.*'));
            $franchise->contests = $contests;*/
            $applications = \SOE\DB\Contest::where(function($query) use ($franchise)
                {
                    $query->where('franchise_id', $franchise->id);
                    $query->orWhere('merchant_id', $franchise->merchant_id);
                })
                ->where('is_demo', 0)
                ->where('is_active', 1)
                ->where('contest_applications.created_at', '<', date('Y-m-d 23:59:59', strtotime($end_day)))
                ->where('contest_applications.created_at', '>', date('Y-m-d 00:00:00', strtotime($start_day)))
                ->join('contest_applications', 'contests.id', '=', 'contest_applications.contest_id')
                ->count();
            $franchise->contest_apps = $applications;

            $franchise->favorites_count = $this->userFavorites->getFranchiseFavorites($franchise->id, date('Y-m-d 00:00:00', strtotime($start_day)), date('Y-m-d 23:59:59', strtotime($end_day)));

            /** Lead Data **/
            $franchise->leads = \SOE\DB\LeadPurchase::where('franchise_id', $franchise->id)
                ->where('purchased_at', '<=', date('Y-m-d 23:59:59', strtotime($end)))
                ->where('purchased_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))
                ->orderBy('purchased_at', 'desc')
                ->get();

            $franchise->errored = 0;
        }
        catch(\Exception $e)
        {
            $franchise->current_views = 0;
            $franchise->current_prints = 0;
            $franchise->current_shares = 0;
            $franchise->contest_apps = 0;
            $franchise->favorites_count = 0;
            $franchise->leads = array();
            $franchise->errored = 1;
        }

        return $franchise;
    }

    public function getEntities($franchise_id, $show_demo = false, $page = 0, $limit = 0)
    {
        $query = \SOE\DB\Entity::join('locations', 'entities.location_id', '=', 'locations.id')
            ->where('entities.is_active', '1')
            ->where('locations.franchise_id', $franchise_id)
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
            ->groupBy('entities.entitiable_id')
            ->groupBy('entities.entitiable_type');
        if($show_demo == false)
        {
            $query->where('entities.is_demo', '=', 0);
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query->take($limit)->skip($limit*$page);
        }
        $query->orderBy('importance');
        $entities = $query->get(array('entities.*', 'locations.city', 'locations.state', \DB::raw("IF(is_dailydeal = 1, 0, IF(entitiable_type = 'Contest', 1, 2)) as importance")));

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
        $contests = \SOE\DB\Contest::whereIn('id', $aContIDs)->get(array('id', 'display_name'));
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

    public function franchiseReport($franchise_id, $start, $end, $offer_limit = 10)
    {
        $franchise = $this->find($franchise_id);

        $start_day = date('Y-m-d', strtotime($start));
        $end_day = date('Y-m-d', strtotime($end));

        $api_key = \Config::get('integrations.mixpanel.key');
        $api_secret = \Config::get('integrations.mixpanel.secret');

        $locations = \SOE\DB\Location::where('franchise_id', $franchise_id)
            ->where('is_active', '1')
            ->where('is_demo', '0')
            ->get(array('id', 'name'));

        $where = '';
        /*if(count($locations))
        {
            $where = '(';
            $aLocIds = array();
            foreach($locations as $location)
            {
                $aLocIds[] = $location->id;
            }
            $where .= implode(' == properties["LocationId"] or ', $aLocIds).' == properties["LocationId"]) and ';
        }
        else
        {
            $where = '';
        }*/
        $mp = new \MPData($api_key, $api_secret);
        $data = $mp->request(array('segmentation'), array(
            'event' => 'Location View',
            'from_date' => $start_day,
            'to_date' => $end_day,
            'unit' => 'day',
            'where' => $where.$franchise->id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));

        $views = 0;
        foreach($data->data->values->{'Location View'} as $key => $value)
        {
            $views += $value;
        }
        $franchise->current_views = $views;

        $mp = new \MPData($api_key, $api_secret);
        $data = $mp->request(array('segmentation'), array(
            'event' => 'Offer Print',
            'from_date' => $start_day,
            'to_date' => $end_day,
            'unit' => 'day',
            'where' => $where.$franchise->id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));

        $prints = 0;
        foreach($data->data->values->{'Offer Print'} as $key => $value)
        {
            $prints += $value;
        }
        $franchise->current_prints = $prints;

        $mp = new \MPData($api_key, $api_secret);
        $data = $mp->request(array('segmentation'), array(
            'event' => 'User Share',
            'from_date' => $start_day,
            'to_date' => $end_day,
            'unit' => 'day',
            'where' => '"'.$franchise_id.'" == properties["FranchiseId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));

        $shares = 0;
        foreach($data->data->values->{'User Share'} as $key => $value)
        {
            $shares += $value;
        }
        $franchise->current_shares = $shares;

        /****** Offer Data ******/

        $offers = \SOE\DB\Offer::where('franchise_id', $franchise_id)
            /*->where('expires_at', '>=', $start)
            ->where('starts_at', '<=', $end)*/
            //->where('is_demo', 0)
            //->where('is_active', 1)
            ->orderBy('expires_at', 'desc')
            ->take($offer_limit)
            ->get();

        if(count($offers))
        {
            $offer_where = array();
            $aOffers = array();
            foreach($offers as $offer)
            {
                $offer_where[] = $offer->id.' == properties["OfferId"]';
                $aOffers[$offer->id] = array('offer' => $offer, 'total_prints' => 0, 'total_views' => 0);
            }
            $offer_where = implode(' or ', $offer_where);
            $offer_where = $where.'('.$offer_where.') and '.$franchise_id.' == properties["FranchiseId"] and properties["Environment"] == "prod"';
            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',
                'where' => $offer_where,
                'type' => 'general',
                'on' => 'properties["OfferId"]'
            ));
            foreach($data->data->values as $key => $values)
            {
                foreach($values as $date => $total)
                    $aOffers[$key]['total_prints'] += $total;
            }

            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Impression',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',
                'where' => $offer_where,
                'type' => 'general',
                'on' => 'properties["OfferId"]'
            ));
            foreach($data->data->values as $key => $values)
            {
                foreach($values as $date => $total)
                    $aOffers[$key]['total_views'] += $total;
            }

            $franchise->offers = $aOffers;
        }
        else
            $franchise->offers = array();

        $contests = \SOE\DB\Contest::leftJoin(\DB::raw(
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
            ->take($offer_limit)
            ->get(array('total_apps.apps', 'contests.*'));
        $franchise->contests = $contests;

        /****** Location Data ******/

        $aLocations = array();
        foreach($locations as $location)
        {
            $aLocations[$location->id] = array('name' => $location->name, 'views' => 0, 'prints' => 0);
        }
        $mp = new \MPData($api_key, $api_secret);
        $data = $mp->request(array('segmentation'), array(
            'event' => 'Location View',
            'from_date' => $start_day,
            'to_date' => $end_day,
            'unit' => 'day',
            'where' => $franchise_id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
            'type' => 'general',
            'on' => 'properties["LocationId"]'
        ));
        foreach($data->data->values as $key => $values)
        {
            $views = 0;
            foreach($values as $date => $total)
                $views += $total;
            if(isset($aLocations[$key]))
                $aLocations[$key]['views'] = $views;
        }

        $mp = new \MPData($api_key, $api_secret);
        $data = $mp->request(array('segmentation'), array(
            'event' => 'Offer Print',
            'from_date' => $start_day,
            'to_date' => $end_day,
            'unit' => 'day',
            'where' => $franchise_id.' == properties["FranchiseId"] and properties["Environment"] == "prod"',
            'type' => 'general',
            'on' => 'properties["LocationId"]'
        ));
        foreach($data->data->values as $key => $values)
        {
            $prints = 0;
            foreach($values as $date => $total)
                $prints += $total;
            if(isset($aLocations[$key]))
                $aLocations[$key]['prints'] = $prints;
        }
        usort($aLocations, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        $franchise->locations = $aLocations;

        /** Lead Data **/
        $franchise->leads = \SOE\DB\LeadPurchase::where('franchise_id', $franchise->id)
            ->where('purchased_at', '<=', date('Y-m-d 23:59:59', strtotime($end)))
            ->where('purchased_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))
            ->orderBy('purchased_at', 'desc')
            ->get();

        return $franchise;
    }
}

/**
 * Handle the Franchise created event.
 *
 * @param SOE\DB\Franchise $merchant
 * @return void
 */
\SOE\DB\Franchise::created(function($franchise)
{
    $appEmail = \App::make('AppEmailInterface');
    if($franchise->primary_contact != '')
    {
        $appEmail->addFranchise($franchise);
        $appEmail->tagEmail($franchise->primary_contact, 'New Merchant MSK');
        $appEmail->tagEmail($franchise->primary_contact, 'Franchise');
        if($franchise->company_id == 11)
            $appEmail->tagEmail($franchise->primary_contact, 'Grand Rapids MSK');
        $appEmail->optIn($franchise->primary_contact);
    }
    if($franchise->certified_at != null || $franchise->sohi_trial_starts_at != null || $franchise->is_dealer == 1)
    {
        $franchiseRepository = \App::make('FranchiseRepositoryInterface');
        $response = $franchiseRepository->netlmsCreate($franchise);
    }
});

/**
 * Handle the Franchise updated event.
 *
 * @param SOE\DB\Franchise $merchant
 * @return void
 */
\SOE\DB\Franchise::updated(function($franchise)
{
    $appEmail = \App::make('AppEmailInterface');
    if($franchise->primary_contact != '')
    {
        if($appEmail->findByEmail($franchise->primary_contact) == 0)
        {
            $appEmail->addFranchise($franchise);
            $appEmail->tagEmail($franchise->primary_contact, 'Franchise');
            $appEmail->tagEmail($franchise->primary_contact, 'New Merchant MSK');
            if($franchise->company_id == 11)
                $appEmail->tagEmail($franchise->primary_contact, 'Grand Rapids MSK');
            $appEmail->optIn($franchise->primary_contact);
        }
    }

    if($franchise->certified_at != null || $franchise->sohi_trial_starts_at != null || $franchise->is_dealer == 1 || $franchise->netlms_id)
    {
        if($franchise->netlms_id)
        {
            $franchise_id = $franchise->id;
            \Queue::push(function($job) use ($franchise_id)
            {
                $franchiseRepository = \App::make('FranchiseRepositoryInterface');
                $franchise = $franchiseRepository->find($franchise_id);
                $response = $franchiseRepository->netlmsSync($franchise);
                $job->delete();
            });
        }
        else
        {
            $franchiseRepository = \App::make('FranchiseRepositoryInterface');
            $response = $franchiseRepository->netlmsCreate($franchise);
        }
    }
});

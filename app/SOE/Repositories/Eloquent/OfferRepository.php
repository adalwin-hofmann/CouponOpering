<?php namespace SOE\Repositories\Eloquent;

class OfferRepository extends BaseRepository implements \OfferRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'location_id',
        'merchant_id',
        'yipitdeal_id',
        'path',
        'path_small',
        'is_dailydeal',
        'special_price',
        'regular_price',
        'code',
        'description',
        'starts_at',
        'expires_at',
        'rating',
        'rating_count',
        'max_redeems',
        'max_prints',
        'url',
        'print_override',
        'is_demo',
        'is_active',
        'created_by',
        'updated_by',
        'savings',
        'is_featured',
        'franchise_id',
        'is_location_specific',
        'deleted_at',
        'stuffing_priority',
        'original_path',
        'old_id',
        'requires_member',
        'is_mobile_only',
        'secondary_type',
        'short_name_line1',
        'short_name_line2',
        'is_followup_for',
        'custom_category_id',
        'custom_subcategory_id',
        'category_visible',
        'secondary_image',
        'hide_expiration',
        'year',
        'make',
        'make_id',
        'model',
        'model_id',
        'is_reoccurring',
        'merchant_logo',
    );

    protected $model = 'Offer';

    public function __construct(
        \FranchiseRepositoryInterface $franchises,
        \VehicleMakeRepositoryInterface $vehicleMakes
    )
    {
        $this->franchises = $franchises;
        $this->vehicleMakes = $vehicleMakes;
        parent::__construct();
    }

    public function leaseQuote($offer_id, $first, $last, $email, $phone, $zipcode)
    {
        $offer = $this->find($offer_id);
        $quote = new \SOE\DB\AutoQuote;
        $quote->quoteable_id = $offer_id;
        $quote->quoteable_type = 'Offer';
        $quote->franchise_id = $offer->franchise_id;
        $quote->first_name = $first;
        $quote->last_name = $last;
        $quote->email = $email;
        $quote->phone = $phone;
        $quote->user_id = \Auth::check() ? \Auth::User()->id : 0;
        $quote->zip = $zipcode;
        $quote->save();

        $data = array();
        $data['provider_id'] = \Config::get('integrations.netlms.provider_id');
        $data['provider_key'] = \Config::get('integrations.netlms.provider_key');
        $data['type'] = 'automotive';
        $data['client'] = array(
            'first' => $first,
            'last' => $last,
            'email' => $email,
            'phone' => $phone,
            'source_ip' => ''
        );

        $data['details'] = array(
            'category' => $offer->make,
            'model' => $offer->model,
            'year' => $offer->year,
            'vin' => '',
            'trim' => '',
            'vendor_inventory_id' => 0,
            'address' => array(
                'line_1' => '',
                'line_2' => '',
                'city' => '',
                'state' => '',
                'zipcode' => $zipcode,
                'country' => ''
            ),
            'notes' => $offer->name
        );
        $franchise = $this->franchises->find($quote->franchise_id);
        $data['directed_to'] = empty($franchise) ? 0 : $franchise->netlms_id;
        $api = \App::make('NetLMSAPIInterface');
        $result = $api->curl('POST', 'lead', null, $data);
        if($result['status'] == 200)
        { 
            $quote->posted_at = date('Y-m-d H:i:s');
            $quote->save();
            $this->trackQuote($quote->id);

            \Event::fire('quote.auto.requested', array($quote));

            $appEmail = \App::make('AppEmailInterface');
            $appEmail->tagEmail($quote->email, 'Requested Auto Quote');
        }
        
        return array('response' => $result['response'], 'quote' => $quote);
    }

    /**
     * Track the given quote in Mixpanel
     *
     * @param integer $quote_id
     * @return void
     */
    public function trackQuote($quote_id)
    {
        $quoteRepo = \App::make('AutoQuoteRepositoryInterface');
        $userRepo = \App::make('UserRepositoryInterface');
        $franchiseRepo = \App::make('FranchiseRepositoryInterface');
        $merchantRepo = \App::make('MerchantRepositoryInterface');
        $categoryRepo = \App::make('CategoryRepositoryInterface');
        $zipcodeRepo = \App::make('ZipcodeRepositoryInterface');
        $quote = $quoteRepo->find($quote_id);
        $user = $userRepo->find($quote->user_id);
        $franchise = $franchiseRepo->find($quote->franchise_id);
        $merchant = empty($franchise) ? array() : $merchantRepo->find($franchise->merchant_id);
        $category = empty($merchant) ? array() : $categoryRepo->find($merchant->category_id);
        $subcategory = empty($merchant) ? array() : $categoryRepo->find($merchant->subcategory_id);
        $zipcode = $zipcodeRepo->findByZipcode($quote->zip);
        $offer = $this->find($quote->quoteable_id);
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify($user ? $user->email : 'non-1');
        $mp->track('Auto Quote Submission', array(
            '$city' => ($zipcode ? $zipcode->city : ''),//$user->city,
            'Environment' => \App::environment(),
            'FranchiseId' => $quote->franchise_id,
            'MerchantId' => empty($franchise) ? 0 : $franchise->merchant_id,
            'MerchantName' => empty($merchant) ? '' : $merchant->display,
            '$region' => ($zipcode ? $zipcode->state : ''),//$user->state,
            'Category' => !empty($category) ? $category->name : '',
            'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
            'Year' => $offer->year,
            'Make' => $offer->make,
            'Model' => $offer->model,
            'Type' => 'Lease'
        ));
    }

    /**
     * Retrieve all offers for the given franchise expiring within the give number of days.
     *
     * @param SOE\DB\Franchise  $franchise
     * @param int               $days Default is 10.
     * @return array
     */
    public function getExpiringByFranchise(\SOE\DB\Franchise $franchise, $days = 10)
    {
        return \SOE\DB\Offer::where('franchise_id', '=', $franchise->id)
                            ->where('expires_at', '>', \DB::raw('NOW()'))
                            ->where('expires_at', '<', \DB::raw("DATE_ADD(NOW(), INTERVAL ".$days." DAY)"))
                            ->get();
    }

    /**
     * Retrieve all active offers for the given franchise.
     *
     * @param SOE\DB\Franchise  $franchise
     * @return array
     */
    public function getActiveByFranchise(\SOE\DB\Franchise $franchise)
    {
        return \SOE\DB\Offer::where('franchise_id', '=', $franchise->id)
                            ->where('expires_at', '>', \DB::raw('NOW()'))
                            ->where('is_active', '=', '1')
                            ->get();
    }

    /**
     * Retrieve all active offers for the given location.
     *
     * @param integer  $location_id
     * @return array
     */
    public function getActiveByLocation($location_id, $recent = false, $page = 0, $limit = 0)
    {
        $entities = \SOE\DB\Entity::where('entitiable_type', 'Offer')
            ->where('location_id', $location_id)
            ->where('is_active', 1)
            ->where('is_demo', 0);
        if(!$recent)
        {
            $entities->where(function($query)
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
        $entities = $entities->groupBy('entitiable_id')
            ->lists('entitiable_id');
        $entities[] = 0;
        $offers = \SOE\DB\Offer::whereIn('id', $entities)
            ->orderBy('expires_at', 'desc');
        $stats = $this->getStats(clone $offers, $limit, $page);
        $offers = $this->paginator($offers, $limit, $page);
        $results = $offers->get();
        $results = array('objects' => $results);
        return array_merge($results, $stats);
    }

    public function statReport($franchise_id, $location_id = 0, $start, $end)
    {
        $start_day = date('Y-m-d', strtotime($start));
        $end_day = date('Y-m-d', strtotime($end));
        $api_key = \Config::get('integrations.mixpanel.key');
        $api_secret = \Config::get('integrations.mixpanel.secret');

        if($location_id)
        {
            $offers = $this->getActiveByLocation($location_id, true, 0, 12)['objects'];
            $where = $location_id.' == properties["LocationId"] and ';
        }
        else
        {
            $offers = $this->query()->where('franchise_id', $franchise_id)
                ->orderBy('expires_at', 'desc')
                ->take(12)
                ->get();
            $where = '';
        }

        if(count($offers))
        {
            $offer_where = array();
            $aOffers = array();
            foreach($offers as $offer)
            {
                $offer_where[] = $offer->id.' == properties["OfferId"]';
                $aOffers[$offer->id] = array('offer' => $offer->toArray(), 'total_prints' => 0, 'total_views' => 0);
            }
            $offer_where = implode(' or ', $offer_where);
            $offer_where = $where.'('.$offer_where.') and '.$franchise_id.' == properties["FranchiseId"] and properties["Environment"] == "prod"';

            try
            {
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
            }
            catch(\Exception $e)
            {
                $aOffers = array();
            }
        }
        else
            $aOffers = array();

        return $aOffers;
    }

    /**
     * Return all coupons matching the given query.
     *
     * @param string $query
     * @param int $page
     * @param int limit
     * @return array
     */
    public function getByQuery($query, $page = 0, $limit = 0)
    {
        $query = $this->with('merchant')
                    ->likeName($query)
                    ->active();
        $stats = $this->getStats(clone $query, $limit, $page);
        $results = $query->setPagination($page, $limit)->get();
        $stats['stats']['returned'] = count($results);
        $return = array('objects' => $results);
        return array_merge($return, $stats);           
    }

    /**
     * Find a offer by white label company id and old offer id.
     *
     * @param int $company_id
     * @param int $offer_id
     * @return mixed
     */
    public function findWhitelabel($company_id, $offer_id)
    {
        return $this->query()->join('franchises', 'franchises.id', '=', 'offers.franchise_id')
                            ->where('offers.old_id', $offer_id)
                            ->where('franchises.company_id', $company_id)
                            ->first(array('offers.*'));
    }

    public function isClipped($offer_id, $user_id)
    {
        return \SOE\DB\UserClipped::on('mysql-write')
                                    ->where('user_id', '=', $user_id)
                                    ->where('offer_id', '=', $offer_id)
                                    ->where('is_deleted', '=', '0')
                                    ->first();
    }

    public function canPrint($offer_id, $column, $user_id)
    {
        $prints = \SOE\DB\UserPrint::on('mysql-write')
                                    ->where($column, '=', $user_id)
                                    ->where('offer_id', '=', $offer_id)
                                    ->count();
        $offer = $this->find($offer_id);
        return $prints < $offer->max_prints;
    }

}


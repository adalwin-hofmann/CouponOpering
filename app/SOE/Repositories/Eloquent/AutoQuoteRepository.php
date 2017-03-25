<?php namespace SOE\Repositories\Eloquent;

class AutoQuoteRepository extends BaseRepository implements \AutoQuoteRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'quoteable_id',
        'quoteable_type',
        'franchise_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'zip',
        'user_id',
        'posted_at',
        'user_ip',
    );

    protected $model = 'AutoQuote';
    protected $franchiseRepository;

    public function __construct(
        \FranchiseRepositoryInterface $franchiseRepository,
        \VehicleEntityRepositoryInterface $vehicleEntities,
        \VehicleMakeRepositoryInterface $vehicleMakes,
        \VehicleStyleRepositoryInterface $vehicleStyles
    )
    {
        $this->franchiseRepository = $franchiseRepository;
        $this->vehicleEntities = $vehicleEntities;
        $this->vehicleMakes = $vehicleMakes;
        $this->vehicleStyles = $vehicleStyles;
        parent::__construct();
    }

    /**
     * Create a new Object with given attributes.
     *
     * @param  array $attributes
     * @return mixed $object
     */
    public function create(array $attributes = array())
    {
        if( $this->isValid('create', $attributes) )
        {
            return parent::create($attributes);
        }

        return false;
    }

    /**
     * Post Auto Quote to NETLMS.
     *
     * @param SOE\DB\AutoQuote $quote
     * @return array
     */
    public function postQuote(\SOE\DB\AutoQuote $quote)
    {
        $data = array();
        $data['provider_id'] = \Config::get('integrations.netlms.provider_id');
        $data['provider_key'] = \Config::get('integrations.netlms.provider_key');
        $data['type'] = 'automotive';
        $data['client'] = array(
            'first' => $quote->first_name,
            'last' => $quote->last_name,
            'email' => $quote->email,
            'phone' => $quote->phone,
            'source_ip' => $quote->user_ip
        );
        $quoteable_pieces = explode('-', $quote->quoteable_type);
        switch ($quoteable_pieces[0]) {
            case 'VehicleEntity':
                $quoteable = $this->vehicleEntities->findByVendorInventoryId($quoteable_pieces[1], $quote->quoteable_id);
                break;
            
            case 'VehicleStyle':
                $quoteable = $this->vehicleStyles->find($quote->quoteable_id);
                break;
        }
        $make = $this->vehicleMakes->find($quoteable->make_id);
        $data['details'] = array(
            'category' => $make->slug,
            'model' => $quoteable_pieces[0] == 'VehicleEntity' ? $quoteable->model : $quoteable->model_name,
            'year' => $quoteable->year,
            'vin' => $quoteable_pieces[0] == 'VehicleEntity' ? $quoteable->vin : '',
            'trim' => $quoteable_pieces[0] == 'VehicleEntity' ? $quoteable->trim_level : '',
            'vendor_inventory_id' => $quoteable_pieces[0] == 'VehicleEntity' ? $quoteable->vendor_inventory_id : 0,
            'address' => array(
                'line_1' => '',
                'line_2' => '',
                'city' => '',
                'state' => '',
                'zipcode' => $quote->zip,
                'country' => ''
            )
        );
        $franchise = $this->franchiseRepository->find($quote->franchise_id);
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
     * Post dealer selections to NETLMS.
     *
     * @param array $data
     * @return void
     */
    public function postDealer($data)
    {
        $data['key'] = \Config::get('integrations.netlms.key');
        \Queue::push(function($job) use ($data)
        {
            $api = \App::make('NetLMSAPIInterface');
            $result = $api->curl('POST', 'lead', null, $data, 'sell-to-dealer');
        });
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
        $quoteable_pieces = explode('-', $quote->quoteable_type);
        switch ($quoteable_pieces[0]) {
            case 'VehicleEntity':
                $quoteable = $this->vehicleEntities->findByVendorInventoryId($quoteable_pieces[1], $quote->quoteable_id);
                break;
            
            case 'VehicleStyle':
                $quoteable = $this->vehicleStyles->find($quote->quoteable_id);
                break;
        }
        $make = $this->vehicleMakes->find($quoteable->make_id);
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
            'Year' => $quoteable->year,
            'Make' => $make->name,
            'Model' => $quoteable_pieces[0] == 'VehicleEntity' ? $quoteable->model : $quoteable->model_name,
            'Type' => $quoteable_pieces[0] == 'VehicleEntity' ? 'Used' : 'New'
        ));
    }

}

/**
 * Handle the AutoQuote created event.
 *
 * @param SOE\DB\AutoQuote $merchant
 * @return void
 */
\SOE\DB\AutoQuote::created(function($quote)
{
    if(stristr($quote->quoteable_type, 'VehicleEntity'))
    {
        /*$quote_id = $quote->id;
        \Queue::push(function($job) use ($quote_id)
        {*/
            $repo = \App::make('AutoQuoteRepositoryInterface');
            //$quote = $repo->find($quote_id);
            $repo->postQuote($quote);
            /*$job->delete();
        });*/
    }

});


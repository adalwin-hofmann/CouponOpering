<?php namespace SOE\Repositories\Eloquent;

class QuoteRepository extends BaseRepository implements \QuoteRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'project_tag_id',
        'project_tag_slug',
        'timeframe',
        'description',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
        'offer_id',
        'submitted_at',
        'posted_at',
        'user_id',
    );

    protected $model = 'Quote';

    public function __construct(
        \FranchiseRepositoryInterface $franchiseRepository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->franchiseRepository = $franchiseRepository;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    public function postQuote(\SOE\DB\Quote $quote)
    {
        $data = array();
        $data['provider_id'] = \Config::get('integrations.netlms.provider_id');
        $data['provider_key'] = \Config::get('integrations.netlms.provider_key');
        $data['type'] = 'home-improvement';
        $data['client'] = array(
            'first' => $quote->first_name,
            'last' => $quote->last_name,
            'email' => $quote->email,
            'phone' => $quote->phone,
        );
        $data['details'] = array(
            'category' => $quote->project_tag_slug,
            'info' => $quote->description,
            'start' => $quote->timeframe,
            'address' => array(
                'line_1' => $quote->address1,
                'line_2' => $quote->address2,
                'city' => $quote->city,
                'state' => $quote->state,
                'zipcode' => $quote->zipcode,
                'country' => $quote->country
            )
        );
        $franchise = $this->franchiseRepository->find($quote->franchise_id);
        $data['directed_to'] = empty($franchise) ? 0 : $franchise->netlms_id;
        $offer_repo = \App::make('OfferRepositoryInterface');
        $offer = $offer_repo->find($quote->offer_id);
        $data['offer'] = empty($offer) ? '' : htmlentities($offer->description);
        $api = \App::make('NetLMSAPIInterface');
        $result = $api->curl('POST', 'lead', null, $data);
        if($result['status'] == 200)
        { 
            $quote->posted_at = date('Y-m-d H:i:s');
            $quote->save();
            $user_id = $quote->user_id;
            \Queue::push(function($job) use ($user_id)
            {
                $appEmail = \App::make('AppEmailInterface');
                $userRepo = \App::make('UserRepositoryInterface');
                $user = $userRepo->find($user_id);
                $appEmail->tagEmail($user->email, 'Requested Project Quote');
            });
        }
        $this->trackQuote($quote->id);
        return $quote;
    }

    /**
     * Track the given quote in Mixpanel
     *
     * @param integer $quote_id
     * @return void
     */
    public function trackQuote($quote_id)
    {
        \Queue::push(function($job) use ($quote_id)
        {
            $quoteRepo = \App::make('QuoteRepositoryInterface');
            $userRepo = \App::make('UserRepositoryInterface');
            $franchiseRepo = \App::make('FranchiseRepositoryInterface');
            $merchantRepo = \App::make('MerchantRepositoryInterface');
            $categoryRepo = \App::make('CategoryRepositoryInterface');
            $quote = $quoteRepo->find($quote_id);
            $user = $userRepo->find($quote->user_id);
            $franchise = $franchiseRepo->find($quote->franchise_id);
            $merchant = empty($franchise) ? array() : $merchantRepo->find($franchise->merchant_id);
            $category = empty($merchant) ? array() : $categoryRepo->find($merchant->category_id);
            $subcategory = empty($merchant) ? array() : $categoryRepo->find($merchant->subcategory_id);
            if(empty($user))
            {
                $job->delete();
                return;
            }
            $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
            $mp->identify($user->email);
            $mp->track('Quote Submission', array(
                '$city' => $quote->city,
                'Environment' => \App::environment(),
                'FranchiseId' => $quote->franchise_id,
                'OfferId' => $quote->offer_id,
                'MerchantId' => empty($franchise) ? 0 : $franchise->merchant_id,
                'MerchantName' => empty($merchant) ? '' : $merchant->display,
                '$region' => $quote->state,
                'Category' => !empty($category) ? $category->name : '',
                'Subcategory' => !empty($subcategory) ? $subcategory->name : ''
            ));

            $job->delete();
        });
    }

}


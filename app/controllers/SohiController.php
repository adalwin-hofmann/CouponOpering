<?php

class SohiController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  | Route::get('/', 'HomeController@showWelcome');
  |
  */
  protected $contractorApplicationRepository;
  protected $projectTagRepository;
  protected $entities;
  protected $featureRepository;
  protected $franchiseRepository;
  protected $merchantRepository;
  protected $offerRepository;
  protected $quoteRepository;
  protected $userRepository;

    /**
    *
    * Create a new controller instance.
    *
    * @param UserRepository $users
    *
    * @return void
    */
    public function __construct(
        ContractorApplicationRepositoryInterface $contractorApplicationRepository,
        ProjectTagRepositoryInterface $projectTagRepository,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $featureRepository,
        FranchiseRepositoryInterface $franchiseRepository,        
        MerchantRepositoryInterface $merchantRepository,
        NonmemberRepositoryInterface $nonmembers,
        OfferRepositoryInterface $offerRepository,
        QuoteRepositoryInterface $quoteRepository,
        SohiSurveyRepositoryInterface $surveys,
        UserRepositoryInterface $userRepository,
        ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->contractorApplicationRepository = $contractorApplicationRepository;
        $this->projectTagRepository = $projectTagRepository;
        $this->entities = $entities;
        $this->featureRepository = $featureRepository;
        $this->franchiseRepository = $franchiseRepository;
        $this->merchantRepository = $merchantRepository;
        $this->nonmembers = $nonmembers;
        $this->offerRepository = $offerRepository;
        $this->quoteRepository = $quoteRepository;
        $this->surveys = $surveys;
        $this->userRepository = $userRepository;
        $this->zipcodes = $zipcodes;
        parent::__construct();
    }

/**
*This function retrieves the index page.
*/

    public function getIndex()
    {
        $code = array();
        $code[] = View::make('sohi.jscode.tour');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('sohi.jscode.homeimprovement');
        $vw = View::make('sohi.index')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Home Improvement - Quality Workmanship at a Fair Price";
        $vw->description = "SaveOn Home Improvement - Quality Workmanship at a Fair Price";
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $vw->geoip = $geoip;
        $vw->tagRepo = $this->projectTagRepository;
        $generic_quote = $this->featureRepository->findByName('generic_quote');
        $vw->generic_quote = empty($generic_quote) ? 0 : $generic_quote->value;
        /*$quote_control = $this->featureRepository->findByName('master_quotes_control');
        $vw->quote_control = empty($quote_control) ? 0 : $quote_control->value;*/

        $quote_control = $this->featureRepository->findByName('master_quotes_control');
        $quote_control = empty($quote_control) ? 0 : $quote_control->value;
        $sohi_markets = ($this->geoip->region_name == 'MI' || $this->geoip->region_name == 'IL' || $this->geoip->region_name == 'MN') ? 1 : 0;
        $vw->sohi_markets = $sohi_markets;
        $vw->quote_control = $quote_control;

        // Load recommendations
        $lat = $this->geoip->latitude;
        $lng = $this->geoip->longitude;
        $zip = $this->zipcodes->getClosest($lat, $lng);
        $geoip = new \StdClass;
        $geoip->city_name = $zip->city;
        $geoip->region_name = $zip->state;
        $geoip->latitude = $lat;
        $geoip->longitude = $lng;
        if(Auth::check())
        {
            $user = Auth::User();
            $recommendations = $this->userRepository->getRecommendations($user, 12, $geoip, 'score', 'sohi');
        }
        else                                                 
        {
            $nonmember = Auth::nonmember();
            $recommendations = $this->nonmembers->getRecommendations($nonmember, 12, $geoip, 'score', 'sohi');
        }                                                       
        $vw->entities = $recommendations['objects'];

        if(!$sohi_markets)
        {
            $user = Auth::check() ? Auth::User() : false;
            $show_demo = !$user ? false : $this->users->showDemo($user);
            $relatedResults = $this->entities->getByCategory($zip->city, $zip->state, $lat, $lng, $show_demo, 'coupon', false, 0, null, 0, 3, 0);
            if(!empty($user))
                $relatedResults['objects'] = $this->users->markClipped($user, $relatedResults['objects']);
            $vw->relatedEntities = $relatedResults['objects'];
        }
        
        return $vw;
    }

    public function getQuote()
    {
        $code = array();
        $vw = View::make('sohi.quotelanding')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getProjecttype()
    {
        $code = array();
        $vw = View::make('sohi.projecttype')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";
        $franchise_id = Input::get('franchise_id', 0);
        $offer_id = Input::get('offer_id', 0);
        if($offer_id != 0 && $franchise_id == 0)
        {
            $offer = $this->offerRepository->find($offer_id);
            $franchise_id = empty($offer) ? 0 : $offer->franchise_id;
        }
        $franchise = $this->franchiseRepository->find($franchise_id);
        $franchise = empty($franchise) ? null : $franchise;
        $franchise_tags = $this->projectTagRepository->getFranchiseTags($franchise);
        $tags = $this->projectTagRepository->getChildTags();
        $aTags = array();
        $aFranchiseTags = array();
        foreach($tags['objects'] as $tag)
        {
            $aTags[] = $tag->id;
        }
        foreach($franchise_tags as $tag)
        {
            $aFranchiseTags[] = $tag->id;
        }
        $vw->offer_id = $offer_id;
        $vw->tags = $tags['objects'];
        $vw->missing_tags = array_diff($aTags, $aFranchiseTags);
        $vw->franchise = $franchise;
        $vw->merchant = empty($franchise) ? null : $this->franchiseRepository->getMerchant($franchise);

        return $vw;
    }

    public function postProjecttype()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'project_tag_id' => 'required'
            )
        );

        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/projecttype')->withErrors($validator)->withInput();
        }

        return Redirect::to('/homeimprovement/projectbrief')->withInput();
    }

    public function getProjectbrief()
    {
        $code = array();
        $vw = View::make('sohi.projectbrief')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        if($quote_id = Input::get('quote_id'))
        {
            $quote = $this->quoteRepository->find($quote_id);
            $vw->projectType = empty($quote) ? '' : $quote->project_tag_id;
            $franchise_id = empty($quote) ? 0 : $quote->franchise_id;
            $vw->timeframe = $quote->timeframe;
            $vw->description = $quote->description;
            $vw->phone = $quote->phone;
            $vw->country = $quote->country;
            $vw->quote_id = $quote_id;
            $vw->zipcode = $quote->zipcode;
            $vw->address1 = $quote->address1;
            $vw->address2 = $quote->address2;
            $vw->city = $quote->city;
            $vw->state = $quote->state;
            $vw->email = $quote->email;
            $vw->first_name = $quote->first_name;
            $vw->last_name = $quote->last_name;
            $vw->offer_id = $quote->offer_id;
        }
        else
        {
            $vw->projectType = Input::old('project_tag_id');    
            $franchise_id = Input::old('franchise_id', 0);
            $vw->quote = null;
            $vw->timeframe = Input::old('timeframe');
            $vw->description = Input::old('description');
            $vw->phone = Input::old('phone');
            $vw->country = Input::old('country');
            $vw->quote_id = 0;
            $vw->zipcode = Input::old('zipcode', (Auth::check() ? Auth::User()->zipcode : ''));
            $vw->address1 = Input::old('address1', (Auth::check() ? Auth::User()->address1 : ''));
            $vw->address2 = Input::old('address2', (Auth::check() ? Auth::User()->address2 : ''));
            $vw->city = Input::old('city', $geoip->city_name);
            $vw->state = Input::old('state', $geoip->region_name);
            $vw->email = Input::old('email', (Auth::check() ? Auth::User()->email : ''));
            $user = Auth::check() ? Auth::User() : null;
            $first_name = '';
            $last_name = '';
            if($user)
            {
                $name = explode(' ', $user->name);
                if(isset($name[0]))
                    $first_name = $name[0];
                if(isset($name[1]))
                    $last_name = $name[1];
            }
            $vw->first_name = Input::old('first_name', $first_name);
            $vw->last_name = Input::old('last_name', $last_name);
            $vw->offer_id = Input::old('offer_id', 0);
        }
        $vw->franchise_id = $franchise_id;
        $vw->geoip = $geoip;
        $franchise = $this->franchiseRepository->find($franchise_id);
        $franchise = empty($franchise) ? null : $franchise;
        $tags = $this->projectTagRepository->getFranchiseTags($franchise);
        $vw->tags = $tags;
        $vw->franchise = $franchise;
        return $vw;
    }

    public function postProjectbrief()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'project_tag_id' => 'required',
                'timeframe' => 'required',
                'description' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address1' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'country' => 'required',
                'password' => 'confirmed'
            )
        );

        $validator->sometimes('email', 'unique:users', function($input)
        {
            return !Auth::check();
        });
        $validator->sometimes(array('password', 'password_confirmation', 'terms'), 'required', function($input)
        {
            return !Auth::check();
        });
        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/projectbrief')->withErrors($validator)->withInput();
        }
        $signup = 'false';
        if(!Auth::check())
        {
            $user = $this->userRepository->blank();
            $user->name = $inputs['first_name'].' '.$inputs['last_name'];
            $user->email = $inputs['email'];
            $user->password = $inputs['password'];
            $user->save();
            $signup = 'true';
            Auth::attempt(array('email'=>$inputs['email'],'password'=>$inputs['password']));
        }

        $tag = $this->projectTagRepository->find($inputs['project_tag_id']);
        $inputs['project_tag_slug'] = empty($tag) ? '' : $tag->slug;
        $user = Auth::User();
        $inputs['user_id'] = empty($user) ? 0 : $user->id;
        if($inputs['quote_id'] == 0)
            $quote = $this->quoteRepository->create($inputs);
        else
            $quote = $this->quoteRepository->update($inputs['quote_id'], $inputs);

        return Redirect::to('/homeimprovement/confirm')->withInput()->with(array('quote_id' => $quote->id, 'signup' => $signup));
    }

    public function getConfirm()
    {
        if(!Auth::check())
            return Redirect::to('/homeimprovement/projectbrief')->withInput();
        $code = array();
        $vw = View::make('sohi.confirm')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";
        $vw->tag = $this->projectTagRepository->find(Input::old('project_tag_id'));
        $vw->quote_id = Session::get('quote_id');
        $vw->signup = Session::get('signup');
        $timeframe = '';
        switch (Input::old('timeframe')) {
            case 'asap':
                $timeframe = "As Soon As Possible";
                break;
            case 'this_week':
                $timeframe = "This Week";
                break;
            case 'this_month':
                $timeframe = "This Month";
                break;
            case '3_month':
                $timeframe = "Over 3 Months";
                break;
        }
        $vw->timeframe = $timeframe;
        $vw->description = Input::old('description');
        return $vw;
    }

    public function postConfirm()
    {
        $quote_id = Input::get('quote_id');
        $quote = $this->quoteRepository->find($quote_id);
        $quote->submitted_at = date('Y-m-d H:i:s');
        $quote->save();
        $this->quoteRepository->postQuote($quote);
        if ($quote->address2=="")
        {
            $data = array(
                'category' => $quote->project_tag_slug,
                'timeframe' => Input::get('timeframe'),
                'description' => $quote->description,
                'name' => $quote->first_name." ".$quote->last_name,
                'email' => $quote->email,
                'phone' => $quote->phone,
                'address' => $quote->address1.", ".$quote->city.", ".$quote->state." ".$quote->zipcode
            );
        } else
        {
            $data = array(
                'category' => $quote->project_tag_slug,
                'timeframe' => Input::get('timeframe'),
                'description' => $quote->description,
                'name' => $quote->first_name." ".$quote->last_name,
                'email' => $quote->email,
                'phone' => $quote->phone,
                'address' => $quote->address1." ".$quote->address2.", ".$quote->city.", ".$quote->state." ".$quote->zipcode
            );
        }
        Mail::send('emails.sohi.newleadreport', $data, function($message)
        {
            $message->to("cmelie@saveon.com")->subject('You Have a New Lead!');
            $message->to("abedor@saveon.com")->subject('You Have a New Lead!');
            $message->to("jstoddard@saveon.com")->subject('You Have a New Lead!');
        });
        return Redirect::to('/homeimprovement/confirm-thanks?qid='.$quote->id);
    }

    public function getConfirmThanks()
    {
        $code = array();
        $code[] = View::make('sohi.jscode.confirm-thanks');
        $vw = View::make('sohi.confirm-thanks')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";
        $vw->quote = $this->quoteRepository->find(Input::get('qid'));
        return $vw;
    }

    public function getGetCertified()
    {
        $code = array();
        $vw = View::make('sohi.get-certified')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        $app_id = Input::get('application_id', 0);
        $app = $this->contractorApplicationRepository->find($app_id);
        $vw->app = $app ? $app : new StdClass;

        return $vw;
    }

    public function postGetCertified()
    {
        $inputs = Input::all();
        $rules = array(
            'business_name' => 'required',
            'primary_contact' => 'required',
            'contact_email' => 'required|email',
            'contact_phone' => 'required',
            'lead_email' => 'required|email',
            'lead_phone' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'country' => 'required',
        );
        $app_id = Input::get('application_id');
        if($app_id == 0)
        {
            $rules['account_password'] = 'required|confirmed';
        }
        $validator = Validator::make(
            $inputs,
            $rules
        );
        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/get-certified')->withInput()->withErrors($validator);
        }

        if($app_id == 0)
            $application = $this->contractorApplicationRepository->create($inputs);
        else
            $application = $this->contractorApplicationRepository->update($app_id, $inputs);
        if(empty($application))
            return Redirect::to('/homeimprovement/get-certified')->withInput()->with(array('system_error' => true));

        return Redirect::to('/homeimprovement/get-certified2?application_id='.$application->id);
    }

    public function getGetCertified2()
    {
        $code = array();
        //$code[] = View::make('sohi.jscode.get-certified2');
        $vw = View::make('sohi.get-certified2')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        $tags = $this->projectTagRepository->getChildTags();
        $vw->tags = $tags['objects'];

        $app_id = Input::has('application_id') ? Input::get('application_id') : Input::old('application_id');
        $existingTags = $this->contractorApplicationRepository->getTags($app_id);
        $aSlugs = array();
        foreach($existingTags as $existing)
        {
            $aSlugs[] = $existing->slug;
        }
        $vw->existingTags = $aSlugs;

        return $vw;
    }

    public function postGetCertified2()
    {
        $app_id = Input::get('application_id');
        $params = Input::all();
        unset($params['application_id']);
        $tags = array_keys($params);
        $tagged = $this->contractorApplicationRepository->setTags($app_id, $tags);
        /*if(!$tagged)
            return Redirect::to('/homeimprovement/get-certified2')->withInput()->with(array('error' => 'Must select at least one!'));*/
        
        return Redirect::to('/homeimprovement/get-certified3')->with(array('application_id' => $app_id));
    }

    public function getGetCertified3()
    {
        $code = array();
        $code[] = View::make('sohi.jscode.get-certified3');
        $vw = View::make('sohi.get-certified3')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postGetCertified3()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'has_outside_labor' => 'required',
                'is_outside_insured' => 'required',
                'is_bbb_accredited' => 'required',
                'does_background_checks' => 'required'
            )
        );
        $validator->sometimes('license_number', 'required', function($input)
        {
            return $input->isLicensed == 1;
        });
        $validator->sometimes('bond_number', 'required', function($input)
        {
            return $input->isBonded == 1;
        });
        $validator->sometimes(array('insurance_company', 'policy_number', 'agent', 'agent_phone'), 'required', function($input)
        {
            return $input->isInsured == 1;
        });
        $validator->sometimes('background_explaination', 'required', function($input)
        {
            return $input->does_background_checks == 0;
        });

        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/get-certified2')->withInput()->withErrors($validator);
        }

        $application = $this->contractorApplicationRepository->find(Input::get('application_id'));
        if(empty($application))
            return Redirect::to('/homeimprovement/get-certified')->withInput()->with(array('system_error' => true));

        $application->license_number = Input::get('license_number');
        $application->bond_number = Input::get('bond_number');
        $application->insurance_company = Input::get('insurance_company');
        $application->policy_number = Input::get('policy_number');
        $application->agent = Input::get('agent');
        $application->agent_phone = Input::get('agent_phone');
        $application->has_outside_labor = Input::get('has_outside_labor');
        $application->is_outside_insured = Input::get('is_outside_insured');
        $application->is_bbb_accredited = Input::get('is_bbb_accredited');
        $application->does_background_checks = Input::get('does_background_checks');
        $application->background_explaination = Input::get('background_explaination');
        $application->additional_info = Input::get('additional_info');
        $application->save();
        $aTags = array();
        $tags = $this->contractorApplicationRepository->getTags($application->id);
        foreach($tags as $tag)
        {
            $aTags[] = $tag->name;
        }
        $data = array(
            'application' => $application->toArray(),
            'tags' => $aTags
        );
        Mail::queueOn('SOE_Tasks', 'emails.sohi.contractor-application', $data, function($message)
        {
            $message->to('cmelie@saveon.com');
            $message->to('abedor@saveon.com');
            $message->subject('New Contractor Application');
        });
        Mail::send('emails.sohi.contractor-application', $data, function($message)
        {
            $message->to("abedor@saveon.com")->subject('New Contractor Application');
            $message->to("jstoddard@saveon.com")->subject('New Contractor Application');
            $message->to("cmelie@saveon.com")->subject('New Contractor Application');
        });
        /*$application_email = $application->contact_email;
        Mail::queueOn('SOE_Tasks', 'emails.sohi.contractor-application-notify', $data, function($message) use ($application_email)
        {
            $message->to($application_email)->subject('Application Submitted');
        });*/

        return Redirect::to('/homeimprovement/get-certified4');
    }

    public function getGetCertified4()
    {
        $code = array();
        $vw = View::make('sohi.get-certified4')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getCoupons($one = '', $two = '')
    {
        
        if($one == '')
        {
            return Redirect::to('/homeimprovement/coupons/all', 301);
        }
        if($two == '')
        {
            return $this->category($one);
        }

        return $this->category($one, $two);
    }

    protected function category($category_slug = null, $subcategory_slug = null)
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('sohi.jscode.category');
        $vw = View::make('sohi.category')->with('code', implode(' ', $code));
        
        $category = $this->projectTagRepository->findBySlug($category_slug);
        $subcategory = $this->projectTagRepository->findBySlug($subcategory_slug);
        $vw->tagRepo = $this->projectTagRepository;
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $vw->geoip = $geoip;
        $vw->category = $category;
        $vw->subcategory = $subcategory;
        $vw->tag_id = empty($subcategory) ? (empty($category) ? 0 : $category->id) : $subcategory->id;
        $vw->active = empty($category) ? 0 : $category->id;
        $vw->type = 'coupon';
        if (empty($subcategory))
        {
            $title = "Save On Coupons in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
            $description = "Save On Coupons in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
        } else {
            $title = ($subcategory->title != '')?$subcategory->title:$subcategory->name;
            $description = ($subcategory->description != '')?$subcategory->description:$subcategory->name." coupons in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name.", ".$subcategory->name." discounts. Restaurant coupons, restaurant deals in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
        }
        $vw->displayType = "Coupons";
        $vw->title = $title;
        $vw->description = $description;
        return $vw;
    }

    public function getCustomersurvey()
    {
        $code = array();
        $vw = View::make('sohi.customersurvey')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postCustomersurvey()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'user_name' => 'required',
                'business_name' => 'required',
                'expected_completion' => 'required',
                'rating' => 'required',
                'work_begun' => 'required',
                'completion_expected' => 'required'
            )
        );
        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/customersurvey')->withInput()->withErrors($validator);
        }
        $inputs['user_id'] = Auth::check() ? Auth::User()->id : 0;
        $inputs['type'] = 'survey';
        $this->surveys->create($inputs);
        return Redirect::to('/homeimprovement/survey-complete');
    }

    public function getSurveyComplete()
    {
        $code = array();
        $vw = View::make('sohi.survey-thanks')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getMerchantreview()
    {
        $code = array();
        $vw = View::make('sohi.merchantreview')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postMerchantreview()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'user_name' => 'required',
                'business_name' => 'required'
            )
        );

        if($validator->fails())
        {
            return Redirect::to('/homeimprovement/merchantreview')->withInput()->withErrors($validator);
        }
        $inputs['user_id'] = Auth::check() ? Auth::User()->id : 0;
        $inputs['type'] = 'review';
        $this->surveys->create($inputs);
        return Redirect::to('/homeimprovement/review-complete');

        return $vw;
    }

    public function getReviewComplete()
    {
        $code = array();
        $vw = View::make('sohi.review-thanks')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

}
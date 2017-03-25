<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    protected $geoip;
    protected $assetRepository;
    protected $entityRepository;
    protected $featureRepository;
    protected $merchantRepository;
    protected $userRepository;
    protected $zipcodeRepository;

    /**
    *
    * Create a new controller instance.
    *
    * @param UserRepository $users
    *
    * @return void
    */
    public function __construct(
        AdvertisementRepositoryInterface $advertisements,
        AssetRepositoryInterface $assetRepository,
        BannerEntityRepositoryInterface $bannerEntities,
        CompanyEventRepositoryInterface $companyEvents,
        CompanyEventAttendeeRepositoryInterface $companyEventAttendees,
        ContestRepositoryInterface $contests,
        ContestAwardDateRepositoryInterface $contestAwardDates,
        ContestDisclaimerRepositoryInterface $contestDisclaimers,
        ContestWinnerRepositoryInterface $contestWinners,
        DistrictRepositoryInterface $districts,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $featureRepository,
        FranchiseRepositoryInterface $franchises,
        LocationRepositoryInterface $locations,
        MerchantRepositoryInterface $merchantRepository,
        NonmemberRepositoryInterface $nonmembers,
        TrackedCallRepositoryInterface $trackedCalls,
        UserLocationRepositoryInterface $userLocations,
        UserRepositoryInterface $userRepository, 
        ZipcodeRepositoryInterface $zipcodeRepository)
    {
        $this->advertisements = $advertisements;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->assetRepository = $assetRepository;
        $this->bannerEntities = $bannerEntities;
        $this->companyEvents = $companyEvents;
        $this->companyEventAttendees = $companyEventAttendees;
        $this->contests = $contests;
        $this->contestAwardDates = $contestAwardDates;
        $this->contestDisclaimers = $contestDisclaimers;
        $this->contestWinners = $contestWinners;
        $this->districts = $districts;
        $this->entities = $entities;
        $this->featureRepository = $featureRepository;
        $this->franchises = $franchises;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->locations = $locations;
        $this->merchantRepository = $merchantRepository;
        $this->nonmembers = $nonmembers;
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->trackedCalls = $trackedCalls;
        $this->userLocations = $userLocations;
        $this->userRepository = $userRepository;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->clickableFactory = new \SOE\Clickables\ClickableFactory;
        $this->zipcodeRepository = $zipcodeRepository;
        parent::__construct();
    }

/**
*This function retrieves the index page.
*/
	public function getIndex()
	{
        if (Auth::check())
        {
            if ($_SERVER['QUERY_STRING'] == '')
            {
                return Redirect::to('/members/dashboard');
            } else {
                return Redirect::to('/members/dashboard?'.$_SERVER['QUERY_STRING']);
            }
        }
        $geoip = json_decode(GeoIp::getGeoIp('json'));

        $special_landing = ($this->featureRepository->findByName('special_landing_page'))?$this->featureRepository->findByName('special_landing_page')->value:0;
        $featured_city_states = ($this->featureRepository->findByName('featured_city_states'))?$this->featureRepository->findByName('featured_city_states')->value:0;

        if (($special_landing == 1) && (strpos($featured_city_states,$geoip->region_name) === false))
        {
            $code = array();
            $code[] = View::make('home.jscode.landing');
            $vw = View::make('home.landing')->with('code', implode(' ', $code));
        } else {
            $code = array();
            $code[] = View::make('home.jscode.masonry');
            $code[] = View::make('home.jscode.index');
            $code[] = View::make('home.jscode.tour');
            $vw = View::make('home.index')->with('code', implode(' ', $code));
        }

        $entity = $this->entities->find(Input::get('showeid', 0));
        if($entity)
        {
            if ($entity->entitiable_type != 'Contest')
            {
                $location = $this->locations->find($entity->location_id);
                if( ! empty($location) )
                    return Redirect::to(URL::abs('/').'/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$entity->category_slug.'/'.$entity->subcategory_slug.'/'.$entity->merchant_slug.'/'.$entity->location_id.'?showeid='.Input::get('showeid'), 301);
                else
                    return Redirect::to(URL::abs('/'), 301);
            } else {
                return Redirect::to(URL::abs('/').'/contests/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all?showeid='.Input::get('showeid'), 301);
            }
            
        }
        
        $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
        $vw->description = "SaveOn is your destination for online coupons and deals.";

        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $city_image = SOE\DB\CityImage::where('state', '=', $geoip->region_name)
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $vw->city_image = $city_image;
        $sohi = $this->featureRepository->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;

        // Load recommendations
        $lat = $this->geoip->latitude; //\Input::get('latitude', '');
        $lng = $this->geoip->longitude; //\Input::get('longitude', '');
        $zip = $this->zipcodeRepository->getClosest($lat, $lng);
        $geoip = new \StdClass;
        $geoip->city_name = $zip->city;
        $geoip->region_name = $zip->state;
        $geoip->latitude = $lat;
        $geoip->longitude = $lng;
        $nonmember = Auth::nonmember();
        $recommendations = $this->nonmembers->getRecommendations($nonmember, 12, $geoip, 'score');
        $vw->entities = $recommendations['objects'];

        $win5k = SOE\DB\Entity::where('name','=','win5k')->where('state','=',$this->geoip->region_name)->where('is_active', '=', '1')->first();
        $vw->win5k = $win5k;

        return $vw;
	}

    public function postClickToCall()
    {
        $number = Input::get('phone_number', "0");
        $formatted = preg_replace("/[^0-9]/", "", $number);
        if(strlen($formatted) != 10)
            return json_encode(array('success' => 'false', 'message' => 'Invalid Phone Number'));

        $location_id = Input::get('location_id', "0");
        if($location_id == "0")
            return json_encode(array('success' => 'false', 'message' => 'Invalid Location'));

        $location = $this->locations->find($location_id);
        if(!$location || $location->redirect_number == '')
            return json_encode(array('success' => 'false', 'message' => 'Invalid Location'));

        $sid = Config::get('integrations.twilio.sid');
        $token = Config::get('integrations.twilio.token');
        $client = new Services_Twilio($sid, $token);
        $call = $client->account->calls->create(
          '+18778698307', // From a valid Twilio number
          $formatted, // Call this number

          // Read TwiML at this URL when a call connects
          URL::abs('/call-redirect').'/'.$location_id
        );
        return json_encode(array('success' => 'true'));
    }

    public function postCallRedirect($location_id)
    {
        $this->trackedCalls->create(array(
            'call_sid' => Input::get('CallSid'),
            'account_sid' => Input::get('AccountSid'),
            'call_from' => Input::get('From'),
            'call_to' => Input::get('To'),
            'direction' => Input::get('Direction'),
            'from_city' => Input::get('FromCity'),
            'from_state' => Input::get('FromState'),
            'from_zip' => Input::get('FromZip'),
            'from_country' => Input::get('FromCountry'),
            'to_city' => Input::get('ToCity'),
            'to_state' => Input::get('ToState'),
            'to_zip' => Input::get('ToZip'),
            'to_country' => Input::get('ToCountry')
        ));

        $twiml = new Services_Twilio_Twiml();
        $location = $this->locations->find($location_id);
        if(!$location || $location->redirect_number == '')
            $twiml->say("I'm sorry, the was an error.");
        else
        {
            $sayMessage = 'Connecting your call.';
            $twiml = new Services_Twilio_Twiml();
            $twiml->say($sayMessage, array('voice' => 'alice'));
            $twiml->dial($location->redirect_number, array(
                'action' => URL::abs('/call-finished'), 
                'method' => 'POST', 
                'record' => 'true'
            ));
        }

        $response = Response::make($twiml, 200);
        $response->header('Content-Type', 'text/xml');
        return $response;
    }

    public function postCallFinished()
    {
        $this->trackedCalls->query()
            ->where('call_sid', Input::get('CallSid'))
            ->update(array(
                'dialcall_sid' => Input::get('DialCallSid'),
                'dialcall_duration' => Input::get('DialCallDuration'),
                'dialcall_status' => Input::get('DialCallStatus'),
                'recording_url' => Input::get('RecordingUrl')
            ));
    }

    public function getCountry($country = null)
    {
        $code = array();
        $countries = SoeHelper::states();
        if($country == null)
        {
            $vw = View::make('home.country')->with('code', implode(' ', $code));
        }
        else
        {
            $country = strtoupper($country);
            if(!isset($countries[$country]))
                return Redirect::to('country');
            $vw = View::make('home.country-states')->with('code', implode(' ', $code));
            $vw->country = strtoupper($country);
            $city_image = SOE\DB\CityImage::where('display', '=', $countries[$country]['name'])->where('region_type','=','Country')->first();
            $vw->city_image = $city_image;
        }
        $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
        $vw->description = "SaveOn is your destination for online coupons and deals.";
        $vw->active = 0;
        $vw->countries = $countries;

        return $vw;
    }

    public function getMobile2($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/mobile2','',$url);
        if ($url == '')
        {
            return Redirect::to('/', 301);
        }
        return Redirect::to($url, 301);
    }

    public function getCookieReset()
    {
        Session::flush();
        return Redirect::to('/');
    }

    public function getNewLogo()
    {
        if (Feature::findByName('new_logo')->value == 1)
        {
            Session::put('new_logo', 'true');
        }
        return Redirect::to('/');
    }

    public function getResetPassword()
    {
        $code = array();
        $vw = View::make('home.reset-password')->with('code', implode(' ', $code));
        $vw->uniq = Input::get('uniq');
        $vw->title = "Password Reset";
        $vw->description = "Reset your SaveOn password to continue receiving fabulous online coupons and deals.";

        return $vw;
    }

    public function postResetPassword()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            )
        );
        if ($validator->fails())
        {
            return Redirect::to('/reset-password')->withErrors($validator)->withInput();
        }
        $uniq = Input::get('uniq');
        $email = Input::get('email');
        $valid = $this->userRepository->validateRecovery($email, $uniq, 'password');
        if(!$valid)
        {
            return Redirect::to('/reset-password')->with('invalid', true)->withInput();
        }

        $user = $this->userRepository->findByEmail($email);
        $user->password = Input::get('password');
        $user->save();
        return Redirect::to('/?modal=signInModal');
    }

    public function getContestVerify()
    {
        $verify_key = Input::old('verify_key') ? Input::old('verify_key') : Input::get('vk');
        $winner = $this->contestWinners->findByVerifyKey($verify_key);
        $contest = $winner ? $this->contests->find($winner->contest_id) : null;
        $code = array();
        $vw = View::make('home.contest-verify')->with('code', implode(' ', $code));
        $vw->winner = $winner;
        $vw->contest = $contest;
        $vw->title = "Contest Verification";
        $vw->width = 'full';
        $vw->description = "Submit your Contest Disclaimer Form to become a winner!";

        return $vw;
    }

    public function postContestVerify()   
    {                                     
        $validator = Validator::make(
            Input::all(),
            array(
                'name' => 'required',
                'check_1' => 'required',
                'check_2' => 'required',
                'check_3' => 'required',
                'check_4' => 'required',
                'check_5' => 'required',
                'check_6' => 'required',
                'verified_at' => 'required',
                'winner_name' => 'required',
                'birth_date' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'verify_key' => 'required'
            )
        );
        
        if($validator->fails())
        {
            return Redirect::to('/contest-verify')->withInput()->withErrors($validator);
        }

        $winner = $this->contestWinners->findByVerifyKey(Input::get('verify_key'));
        if(!$winner)
            return Redirect::to('/contest-verify');

        $contest_disclaimer = $this->contestDisclaimers->create(array(
            'contest_id' => $winner->contest_id,
            'contest_award_date_id' => $winner->award_date_id,
            'name' => Input::get('name'),
            'check_1' => Input::has('check_1') ? 1 : 0,
            'check_2' => Input::has('check_2') ? 1 : 0,
            'check_3' => Input::has('check_3') ? 1 : 0,
            'check_4' => Input::has('check_4') ? 1 : 0,
            'check_5' => Input::has('check_5') ? 1 : 0,
            'check_6' => Input::has('check_6') ? 1 : 0,
            'verified_at' => date('Y-m-d H:i:s'),
            'winner_name' => Input::get('winner_name'),
            'birth_date' => Input::get('birth_date'),
            'address' => Input::get('address'),
            'city_state_zip' => Input::get('city').', '.Input::get('state').' '.Input::get('zip'),
            'daytime_phone' => Input::get('phone'),
            'email' => Input::get('email'),
            'contest_winner_id' => $winner->id,
        ));
         
        $this->contestWinners->verified($winner->id);

        $winner->email = Input::get('email');
        $winner->address = Input::get('address');
        $winner->city = Input::get('city');
        $winner->state = Input::get('state');
        $winner->zip = Input::get('zip');
        $winner->save();

        $contest = $this->contests->find($winner->contest_id);
        if($contest->current_inventory != 0)
        {
            $contest->current_inventory = $contest->current_inventory - 1;
            $contest->save();
        }
        if($contest->total_inventory != 0 && $contest->current_inventory == 0)
        {
            $contest->date_ended = date("Y-m-d H:i:s");
            $contest->save();
        }

        $date = $this->contestAwardDates->find($winner->award_date_id);
        $admins = $this->featureRepository->findByName('contest_admins', false);
        $admins = $admins ? $admins->value : 'abedor@saveon.com';
        $admins = explode(',', $admins);
        
        $data = array(
                'contest' => $contest->toArray(),
                'date' => $date->toArray(),
                'contest_disclaimer' => $contest_disclaimer->toArray(),
                'contest_winner' => $winner->toArray()
            );
          
        Mail::send('emails.contest-winner', $data, function($message) use ($admins)
        {
            //$message->subject('SaveOn - Winner Selected');
            /*foreach($admins as $admin)
            {
                $message->to($admin, '');
            }*/
            $message->to("ryan.patel215@gmail.com")->subject('SaveOn - Winner Selected');
            $message->to("keithb@nwmarketingco.com")->subject('SaveOn - Winner Selected');
            $message->to("abedor@saveon.com")->subject('SaveOn - Winner Selected');
            $message->to("cmelie@saveon.com")->subject('SaveOn - Winner Selected');    
            $message->to($winner->email)->subject('SaveOn - Winner Selected');                
        });
        
        if($contest->is_automated)
        {
            return Redirect::to('/contest-reward?vk='.Input::get('verify_key'));
        } else {
            return Redirect::to('/contest-reward-mail?vk='.Input::get('verify_key'));
        }
            
    }

    public function getContestReward()
    {
        $winner = $this->contestWinners->getWinnerByKey(Input::get('vk', 0));
        $demo = $this->contestAwardDates->find(Input::get('demo', 0));

        if(!$winner && !$demo)
            return Redirect::to('/');

        if($demo)
        {
            $winner = $this->contestWinners->blank();
            $winner->first_name = 'DEMO';
            $winner->last_name = ' - THIS GIFT CERTIFICATE IS NOT VALID';
            $contest = $this->contests->find($demo->contest_id);
            $date = $demo;
        }
        else
        {
            $contest = $this->contests->find($winner->contest_id);
            $date = $this->contestAwardDates->find($winner->award_date_id);
        }

        if($date->has_prize == 0)
            return Redirect::to('/');

        $code = array();
        $code[] = View::make('home.jscode.contest-reward');
        $vw = View::make('home.contest-reward')->with('code', implode(' ', $code));
        
        $vw->winner = $winner;
        $vw->contest = $contest;
        $vw->date = $date;
        $merchant = $this->merchantRepository->find($contest->merchant_id);
        $vw->merchant = $merchant;
        $vw->logo = $this->assetRepository->getLogo($merchant);
        
        $vw->title = "Claim Contest Prize";
        $vw->width = 'full';
        $vw->description = "Claim Contest Prize!";
        return $vw;
    }

    public function getContestRewardMail()
    {
        $winner = $this->contestWinners->getWinnerByKey(Input::get('vk', 0));
        $demo = $this->contestAwardDates->find(Input::get('demo', 0));

        if(!$winner && !$demo)
            return Redirect::to('/');

        if($demo)
        {
            $winner = $this->contestWinners->blank();
            $winner->first_name = 'DEMO';
            $winner->last_name = ' - THIS GIFT CERTIFICATE IS NOT VALID';
            $contest = $this->contests->find($demo->contest_id);
            $date = $demo;
        }
        else
        {
            $contest = $this->contests->find($winner->contest_id);
            $date = $this->contestAwardDates->find($winner->award_date_id);
        }

        if($date->has_prize == 0)
            return Redirect::to('/');

        $code = array();
        //$code[] = View::make('home.jscode.contest-reward');
        $vw = View::make('home.contest-reward-mail')->with('code', implode(' ', $code));
        $vw->winner = $winner;
        $vw->contest = $contest;
        $vw->date = $date;
        $merchant = $this->merchantRepository->find($contest->merchant_id);
        $vw->merchant = $merchant;
        $vw->logo = $this->assetRepository->getLogo($merchant);
        $vw->title = "Look For Your Contest Prize";
        $vw->width = 'full';
        $vw->description = "Look For Your Contest Prize!";
        return $vw;
    }

    public function getContestLetter()
    {
        $winner = $this->contestWinners->getWinnerByKey(Input::get('vk', 0));
        $demo = $this->contestAwardDates->find(Input::get('demo', 0));

        if(!$winner && !$demo)
            return Redirect::to('/');

        if($demo)
        {
            $winner = $this->contestWinners->blank();
            $winner->first_name = 'DEMO';
            $winner->last_name = ' - THIS GIFT CERTIFICATE IS NOT VALID';
            $contest = $this->contests->find($demo->contest_id);
            $date = $demo;
        }
        else
        {
            $contest = $this->contests->find($winner->contest_id);
            $date = $this->contestAwardDates->find($winner->award_date_id);
        }

        if($date->has_prize == 0)
            return Redirect::to('/');

        $code = array();
        $code[] = View::make('home.jscode.contest-reward');
        $vw = View::make('home.contest-letter')->with('code', implode(' ', $code));
        $vw->winner = $winner;
        $vw->contest = $contest;
        $vw->date = $date;
        $merchant = $this->merchantRepository->find($contest->merchant_id);
        $vw->merchant = $merchant;
        $vw->logo = $this->assetRepository->getLogo($merchant);
        $vw->title = "Claim Contest Prize";
        $vw->width = 'full';
        $vw->description = "Claim Contest Prize!";
        return $vw;
    }

    public function getVerifyEmail()
    {
        $code = array();
        $code[] = View::make('home.jscode.verify-email');
        $vw = View::make('home.verify-email')->with('code', implode(' ', $code));
        $email = Input::get('email');
        $uniq = Input::get('uniq');
        if(!$email || !$uniq)
            return Redirect::to('/');
        $vw->email = $email;
        $valid = $this->userRepository->validateRecovery($email, $uniq, 'verification');
        if($valid)
        {
            $user = $this->userRepository->findByEmail($email);
            if(!empty($user))
            {
                $user->is_email_valid = 1;
                $user->save();
                $user_id = $user->id;
                Queue::push(function($job) use ($user_id)
                {
                    $appEmail = App::make('AppEmailInterface');
                    $repo = App::make('UserRepositoryInterface');
                    $user = $repo->find($user_id);
                    $appEmail->optIn($user->email);
                    $appEmail->tagEmail($user->email, 'Start Welcome Sequence');
                    // Get recommendations for welcome email
                    $geoip = new StdClass;
                    $geoip->city_name = empty($user->city) ? 'Troy' : $user->city;
                    $geoip->region_name = empty($user->city) ? 'MI' : $user->state;
                    $geoip->latitude = empty($user->city) ? 42.58 : $user->latitude;
                    $geoip->longitude = empty($user->city) ? -83.14 : $user->longitude;
                    $recommendations = $repo->getRecommendations($user, $limit = 2, $geoip, $ordering = 'score', $type = 'soe');
                    /*$data = array(
                        'name' => $user->name,
                        'recommendations' => $recommendations['objects']
                    );

                    $email = $user->email;
                    Mail::send('emails.welcome', $data, function($message) use ($email)
                    {
                        $message->to($email)->subject('Welcome To SaveOn!');
                        $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
                    });*/

                    // Send welcome email through InfusionSoft
                    $view = View::make('emails.welcome');
                    $view->name = $user->name;
                    $view->recommendations = $recommendations['objects'];
                    $html = $view->render();
                    $response = $appEmail->sendEmail('Welcome To SaveOn!', $html, $user->email);
                    if($response)
                        $appEmail->tagEmail($user->email, 'Welcome Email Sent');

                    $job->delete();
                });
            }
        }
        $vw->valid = $valid;
        $vw->title = "Email Verification";
        $vw->description = "Verify your email to begin receiving fabulous online coupons and deals.";

        return $vw;
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    public function getContestsOld($one = '', $two = '', $three = '')
    {
        if($eid = Input::get('showeid'))
        {
            $entity = $this->entityRepository->find($eid);
            if($entity && !$entity->is_active || !$entity)
                return Redirect::to('/contests/all', 301);
        }       
        if($one == '')
        {
            return Redirect::to('/contests/all', 301);
        }
        if($two == '')
        {
            return $this->category($one, 'contest');
        }
        if($three == '')
        {
            return $this->category($two, 'contest');
        }
        return Redirect::to('/');
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    public function getDailydealsOld($one = '', $two = '', $three = '')
    {
        if($one == '')
        {
            return Redirect::to('/dailydeals/all', 301);
        }
        if($two == '')
        {
            return $this->category($one, 'dailydeal');
        }
        if($three == '')
        {
            return $this->category($two, 'dailydeal');
        }
        return Redirect::to('/');
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    public function getCouponsOld($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        
        if($one == '')
        {
            return Redirect::to('/coupons/all', 301);
        }
        if($two == '')
        {
            if($one == 'home-services')
                return Redirect::to('/coupons/home-improvement', 301);
            return $this->category(str_replace("_","-",$one));
        }
        if($three == '')
        {
            if(strtolower($one) == 'in')
            {
                return $this->couponsIn($two);
            }
            if(is_numeric($one))
            {
                return $this->category($one, SoeHelper::fixSlug($two));
            }
            else
            {
                if($one == 'home-services')
                    return Redirect::to('/coupons/home-improvement/'.$two, 301);
                return $this->category($two);
            }
        }
        if($four == '')
        {
            if(strtolower($one) == 'in')
            {
                return $this->couponsIn($two, $three);
            }
            else
            {
                if($one == 'home-services')
                    return Redirect::to('/coupons/home-improvement/'.$two.'/'.$three, 301);

                $geoip = json_decode(GeoIp::getGeoIp('json'));
                $nearest = Merchant::findNearestLocationBySlug($three, $geoip->latitude, $geoip->longitude);
                if(empty($nearest))
                {
                    return $this->category(SoeHelper::fixSlug($two));
                }

                return $this->merchant($nearest->merchant_slug, $nearest->id);
            }
        }
        if($five == '')
        {
            if(is_numeric($one))
            {
                return $this->category($one, SoeHelper::fixSlug($two), $three, $four);
            }
            else
            {
                if($one == 'home-services')
                {
                    $eid = Input::get('showeid', 0);
                    return Redirect::to('/coupons/home-improvement/'.$two.'/'.$three.'/'.$four.($eid != 0 ? '?showeid='.$eid : ''), 301);
                }
                return $this->merchant($three, $four, Input::get('showeid', 0));
            }
        }

        return $this->category($one, SoeHelper::fixSlug($two), $three, SoeHelper::fixSlug($four), $five, $six);
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    protected function category($category_slug = null, $type = 'coupon', $entity_id='', $subslug='', $city='', $state='')
    {
        $category = Category::find($entity_id);
        $categoryslug = Category::findBySlug($subslug);
        if (($category == $categoryslug) && !empty($category))
        {
            $parent = Category::find($category->parent_id);
            if(!empty($parent))
            {
                return Redirect::to('/coupons/'.$parent->slug.'/'.$category->slug, 301);
            }
            else
            {
                return Redirect::to('/');
            }
        } else {
            $category = Category::find($category_slug);
            $categoryslug = Category::findBySlug($type);
            if (($category == $categoryslug) && !empty($category))
            {
                return Redirect::to('/coupons/'.$category->slug, 301);
            }
        }

        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.category');
        $vw = View::make('home.category')->with('code', implode(' ', $code));

        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            $vw->entity = $entity;
        }
        
        $category = Category::findBySlug($category_slug);
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $vw->geoip = $geoip;
        $vw->category = $category;
        $vw->category_id = empty($category) ? 0 : $category->id;
        $vw->category_slug = empty($category) ? 0 : $category->slug;
        $vw->parent_id = empty($category) ? 0 : $category->parent_id;
        $parent_category = Category::find(empty($category) ? 0 : $category->parent_id);
        $vw->parent_slug = (empty($parent_category)) ? 0 : $parent_category->slug;
        $vw->parent_name = (empty($parent_category)) ? 0 : $parent_category->name;
        $vw->active = empty($category) ? 0 : ($category->parent_id == 0 ? $category->id : $category->parent_id);
        $vw->type = $type;
        if ($type == 'coupon')
        {
            $displayType = "Coupons";
        } 
        elseif ($type == 'dailydeal')
        {
            $displayType = "Deals";
        } 
        else 
        {
            $displayType = "Contests";
            // Get Win5k
            $entity = SOE\DB\Entity::where('name','=','win5k')->where('state','=',$geoip->region_name)->where('is_active', '=', '1')->first();
            $vw->win5k = $entity;
        }
        if (empty($category))
        {
            $title = "SaveOn ".$displayType." in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
            $description = "SaveOn ".$displayType." in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
        } else {
            $title = ($category->title != '')?$category->title:$category->name;
            $description = ($category->description != '')?$category->description:$category->name." coupons in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name.", ".$category->name." discounts. Restaurant coupons, restaurant deals in ".ucwords(strtolower($geoip->city_name)).", ".$geoip->region_name;
        }
        $vw->displayType = $displayType;
        $vw->title = $title;
        $vw->description = $description;
        return $vw;
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    protected function couponsIn($citystate,$state='')
    {
        $sohi = $this->featureRepository->findByName('home_improvement');
        $citystate = strtoupper($citystate);
        $state = $state != '' ? strtoupper($state) : '';
        $code = array();
        $countries = SoeHelper::states();
        if($state == '')
        {
            $found = false;
            $stateName = '';
            foreach($countries as $country => $data)
            {
                foreach($data['states'] as $abbr => $name)
                {
                    if($citystate == $abbr)
                    {
                        $found = true;
                        $stateName = $name;
                    }
                }
            }
            if(!$found)
                return Redirect::to('country');
            $vw = View::make('home.country-cities')->with('code', implode(' ', $code));
            $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
            $vw->description = "SaveOn";
            $vw->active = 0;
            $vw->stateName = $stateName;
            $vw->state = $citystate;
            $vw->cities = Zipcode::getTopByState($citystate);
            $city_image = SOE\DB\CityImage::where('state', '=', strtoupper($citystate))->where('region_type','=','State')->first();
            $vw->city_image = $city_image;
            $vw->sohi = empty($sohi) ? 0 : $sohi->value;

            return $vw;
        }
        else
        {
            $zipcode = Zipcode::getByQuery(str_replace('-',' ',$citystate).','.$state, 0, 1);
            if($zipcode['stats']['returned'] == 0)
            {
                return Redirect::to('country');
            }
            //$user = Auth::check() ? Auth::User() : Auth::Nonmember();
            //$user->setLocation($zipcode['objects'][0]->latitude, $zipcode['objects'][0]->longitude, $zipcode['objects'][0]->city, $zipcode['objects'][0]->state);
            $this->userLocations->setLocation(Auth::person(), $zipcode['objects'][0]->latitude, $zipcode['objects'][0]->longitude, $zipcode['objects'][0]->city, $zipcode['objects'][0]->state);
            
            $code = array();
            $code[] = View::make('home.jscode.masonry');
            $code[] = View::make('home.jscode.index');
            $vw = View::make('home.index')->with('code', implode(' ', $code));
            $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
            $vw->description = "SaveOn";

            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
            $city_image = SOE\DB\CityImage::where('state', '=', $geoip->region_name)
                ->where('region_type', '=', 'City')
                ->orderBy('distance', 'asc')
                ->first(array(
                    'city_images.*',
                    DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
            $vw->city_image = $city_image;
            if (!empty($city_image))
            {
                $vw->city_desc = $city_image->about;
            }
            $vw->sohi = empty($sohi) ? 0 : $sohi->value;
            return $vw;
        }
    }

    /**
     * DEPRECATED! See Coupons Contoller.
     * Caleb Beery 5/2/2014
     */
    protected function merchant($slug, $locationid, $entity_id = 0)
    {
        // Handle Location Coupons
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.merchant');

        $location = Location::find($locationid);
        if(!$location)
        {
            $merchant = $this->merchantRepository->findBySlug($slug);
            if(!$merchant)
                App::abort(404);
            return $this->getCoupons($merchant->category->slug, $merchant->subcategory->slug, $merchant->slug);
        }
        $merchant = $this->merchantRepository->with('makes')->find($location->merchant_id);
        $make_ids = '';
        foreach($merchant->makes as $make)
        {
            $make_ids .= $make->id.',';
        }
        $make_ids = trim($make_ids,',');
        $franchise = Franchise::find($location->franchise_id);
        if(!$franchise)
            App::abort(404);
        
        if($franchise->is_dealer)
        {
            $code[] = View::make('soct.jscode.search');
        }

        // Add Merchant/Location View
        $viewer = Auth::check() ? Auth::User() : Auth::Nonmember();
        $viewer->view($location);

        $reviews = $location->getReviews();
        $is_reviewed = 0;
        $is_favorited = 0;
        if(Auth::check())
        {
            $reviewFilters = array(array('type'=>'where','key'=>'reviewable_id','operator'=>'=','value'=>$location->id),
                array('type'=>'where','key'=>'reviewable_type','operator'=>'=','value'=>'Location'),
                array('key' => 'user_id', 'operator' => '=', 'value' => Auth::User()->id),
                array('key' => 'is_deleted', 'operator' => '=', 'value' => '0'));
            $my_review = Review::get($reviewFilters);
            $is_reviewed = $my_review['stats']['returned'] == 0 ? 0: 1 ;
            $fav = UserFavorite::get(array(array('key' => 'user_id', 'operator' => '=', 'value' => Auth::User()->id),
                                        array('key' => 'favoritable_type', 'operator' => '=', 'value' => 'SOE\\DB\\Location'), 
                                        array('key' => 'favoritable_id', 'operator' => '=', 'value' => $locationid), 
                                        array('key' => 'is_deleted', 'operator' => '=', 'value' => '0') ));
            $is_favorited = $fav['stats']['returned'] == 0 ? 0 : 1;
        }

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $vw = View::make('home.merchant')->with('code', implode(' ', $code));
        $vw->location = $location;
        $vw->merchant = $merchant;
        $vw->make_ids = $make_ids;
        $vw->franchise = $franchise;
        $vw->is_reviewed = $is_reviewed;
        $vw->is_favorited = $is_favorited;
        $locationFilter = array(array('type'=>'where','key'=>'merchant_id','operator'=>'=','value'=>$merchant->id),
            array('type'=>'where','key'=>'is_national','operator'=>'!=','value'=>'1'));
        $allLocations = Location::get($locationFilter, 1, 0);
        $vw->locationCount = $allLocations['stats']['total'];

        $aProperties = array('address' => '\[address\]', 'city' => '\[city\]', 'state' => '\[state\]', 'phone' => '\[phone\]', 'website' => '\[website\]');
        $about_text = $location->about ? $location->about : $merchant->about;
        foreach($aProperties as $property => $regex) 
        {
            $about_text = preg_replace('/'.$regex.'/', $location->$property, $about_text);
        }
        $about_text = preg_replace('/\[merchant\]/', $merchant->display, $about_text);
        
        if(preg_match_all('/\{[^\{]+\}/', $about_text, $matches))
        {
            foreach($matches[0] as $match)
            {
                $terms = preg_replace('/[{}]/', '', $match);
                $aTerms = explode(',', $terms);
                $about_text = str_replace($match, $aTerms[rand(0,count($aTerms)-1)], $about_text);
            }
        }
        $vw->about_text = $about_text;

        $category = Category::find($merchant->category_id);
        $vw->catName = $category->name;
        $vw->catSlug = $category->slug;
        $vw->catId = $category->id;

        $subcategory = Category::find($merchant->subcategory_id);
        $vw->subcatName = $subcategory->name;
        $vw->subcatSlug = $subcategory->slug;
        $vw->subcatId = $subcategory->id;
        $vw->logo = $this->assetRepository->getLogo($merchant);
        $vw->images = $this->assetRepository->getMerchantImages($merchant);
        $vw->videos = $this->assetRepository->getMerchantVideos($merchant);
        $vw->pdfs = $this->assetRepository->getMerchantPdfs($merchant);
        $vw->reviews = $reviews;
        //$vw->similar = Entity::getSimilar($merchant, Auth::check() ? Auth::User() : Auth::Nonmember());
        $vw->user = Auth::check() ? Auth::User() : Auth::Nonmember();
        $quote_control = Feature::findByName('master_quotes_control');
        $quote_control = empty($quote_control) ? 0 : $quote_control->value;
        $detroit_quote_control = Feature::findByName('detroit_quotes_only');
        $detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
        if($detroit_quote_control)
        {
            $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
            $detroit_quote_control = ($distance < $detroit_quote_control && $geoip->region_name == 'MI') ? 1 : 0;
            $vw->quote_control = $quote_control && $detroit_quote_control;
        }
        else
        {
            $vw->quote_control = $quote_control;
        }

        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            $vw->entity = $entity;
        }
        $company = SOE\DB\Company::where('id', '=', $location->company_id)->first();
        if(!empty($company))
        {
            if($company->logo_image != "")
            {
                $vw->company = $company;
            }
        }
        $page_title = $location->page_title ? $location->page_title : $merchant->page_title;
        $meta_description = $location->meta_description ? $location->meta_description : $merchant->meta_description;
        $vw->title = $page_title ==''? $merchant->display." in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name." | Coupons to SaveOn ". $subcategory->name." and " .$category->name: $page_title;
        $vw->description = $meta_description ==''? "Contact ".$merchant->display." for local ".$subcategory->name." Coupons and discounts in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name." find the support you need to cut back on ".$category->name." expenses at SaveOn." : $meta_description;
        $vw->geoip = $geoip;

        $special_merchant = '';
        if($franchise->is_dealer)
        {
            $special_merchant = 'soct';
        }
        $vw->special_merchant = $special_merchant;

        return $vw;
    }

    /**
     * DEPRECATED! See Directions Contoller.
     * Caleb Beery 5/2/2014
     */
    public function getDirectionsOld($merchant_slug, $merchantid = null)
    {
        $code = array();
        if($merchantid)
            $merchant = Merchant::find($merchantid);
        else
        {

            $merchants = Merchant::getBySlug($merchant_slug);
            if($merchants['stats']['returned'] > 0)
                $merchant = $merchants['objects'][0];
            else
                return Redirect::to('/');
        }
        $code[] = View::make('home.jscode.directions');
        $vw = View::make('home.directions')->with('code', implode(' ', $code));
        $vw->merchant = $merchant;

        $category = Category::find($merchant->category_id);
        $vw->catName = $category->name;
        $vw->catSlug = $category->slug;

        $subcategory = Category::find($merchant->subcategory_id);
        $vw->subcatName = $subcategory->name;
        $vw->subcatSlug = $subcategory->slug;
        //$vw->similar = Entity::getSimilar($merchant, Auth::check() ? Auth::User() : Auth::Nonmember());

        $vw->title = "Get Directions to ".$merchant->display." in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name." | ".$subcategory->name." Coupons, SaveOn";
        $vw->description = $merchant->meta_description == '' ? "Find directions to a ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name." ".$merchant->display." near you coupled with local ". $subcategory->name." coupons and discounts." : $merchant->meta_description;

        return $vw;
    }

    public function getFeatured($in='in',$city='',$state='')
    {
        $zipcode = Zipcode::getByQuery(str_replace('-',' ',$city).','.$state, 0, 1);
        if($zipcode['stats']['returned'] == 0)
        {
            return Redirect::to('country');
        }
        //$user = Auth::check() ? Auth::User() : Auth::Nonmember();
        //$user->setLocation($zipcode['objects'][0]->latitude, $zipcode['objects'][0]->longitude, $zipcode['objects'][0]->city, $zipcode['objects'][0]->state);
        $this->userLocations->setLocation(Auth::person(), $zipcode['objects'][0]->latitude, $zipcode['objects'][0]->longitude, $zipcode['objects'][0]->city, $zipcode['objects'][0]->state);

        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.index');
        $vw = View::make('home.cityfeature')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
        $vw->description = "SaveOn";

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $city_image = SOE\DB\CityImage::where('state', '=', $geoip->region_name)
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $vw->city_image = $city_image;
        if (!empty($city_image))
        {
            $vw->city_sidebar_desc = $city_image->about;
        }
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        return $vw;
    }

    public function getSearch()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.search');
        $vw = View::make('home.searchresults')->with('code', implode(' ', $code));
        $vw->title = "Search Results | SaveOn";
        $vw->description = "Your Search Results from SaveOn.com, finding you the offers and discounts you need.";

        $query = Input::get('q');
        $type = (Input::get('t')!='')?Input::get('t'):'merchant';
        $vw->query = $query;
        $vw->searchType = $type;
        $vw->geoip = $this->geoip;

        $vw->width = 'full';
        $vw->search_page = 'true';
        $vw->search_version = 2;

        return $vw;
    }    

    public function getSuggestmerchant()
    {
        $code = array();
        $code[] = View::make('home.jscode.suggestmerchant');
        $vw = View::make('home.suggestmerchant')->with('code', implode(' ', $code));
        $vw->title = "Suggest A Merchant | SaveOn";
        $vw->description = "Suggesting a merchant here can connect you to offers tailored to you. SaveOn.com aims to provide you with coupons, discounts, and deals to help you SaveOn everything you need.";

        return $vw;

    }

    public function getFeedback()
    {
        $code = array();
        $vw = View::make('home.feedback')->with('code', implode(' ', $code));
        $vw->title = "Send Us Feedback | SaveOn";
        $vw->description = "SaveOn values consumer feedback so that we can provide the best offers and deals for our members.";

        return $vw;

    }

    public function getFaqs()
    {
        $code = array();
        $vw = View::make('home.faqs')->with('code', implode(' ', $code));
        $vw->title = "Frequently Asked Questions | SaveOn";
        $vw->description = "A list of questions our users frequently ask about our coupons, deals, contests, and offers.";

        return $vw;

	}

    public function getAdvertisingFaqs()
    {
        $code = array();
        $vw = View::make('home.advertising-faqs')->with('code', implode(' ', $code));
        $vw->title = "Advertsing Frequently Asked Questions | SaveOn";
        $vw->description = "A list of questions our merchants frequently ask in order to bring you the best coupons, deals, and offers.";

        return $vw;

    }

    public function getHeritage()
    {
        $code = array();
        $vw = View::make('home.heritage')->with('code', implode(' ', $code));
        $vw->title = "SaveOn's Heritage | You Dream It, We'll Build It";
        $vw->description = "Our Heritage: You Dream It, We'll Build It.  The story of how SaveOn became your home for the best deals in your town";

        return $vw;

    }

    public function getHeadquarters()
    {
        $code = array();
        $code[] = View::make('home.jscode.headquarters');
        $vw = View::make('home.headquarters')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Headquarters | Troy, Michigan";
        $vw->description = "Those of us at Headquarters in Troy, Michigan are committed to bringing you the best deals around.";

        return $vw;

    }

    public function getOurteam()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $vw = View::make('home.ourteam')->with('code', implode(' ', $code));
        $vw->title = "SaveOn's Team";
        $vw->description = "";

        return $vw;

    }

    public function getMerchantservices()
    {
        $code = array();
        $vw = View::make('home.merchantservices')->with('code', implode(' ', $code));
        $vw->title = "Merchant Services | SaveOn";
        $vw->description = "We here at SaveOn aim to give our merchants all the tools they need to succeed in bringing you coupons, deals, and discounts nationally.";

        return $vw;

    }

    public function getNews()
    {
        $code = array();
        $vw = View::make('home.news')->with('code', implode(' ', $code));
        $vw->title = "News | SaveOn ";
        $vw->description = "The latest news about the latest deals; see what's going on with SaveOn!";

        return $vw;

    }

    public function getCareers()
    {
        $code = array();
        $vw = View::make('home.careers')->with('code', implode(' ', $code));
        $vw->title = "Careers | Work for SaveOn";
        $vw->description = "Are you passionate about coupons? Do you like getting people deals? If you live and breathe offers, come work for SaveOn!";

        return $vw;

    }

    public function getPresskit()
    {
        $code = array();
        $vw = View::make('home.presskit')->with('code', implode(' ', $code));
        $vw->title = "Press Kit | SaveOn";
        $vw->description = "Get website screenshots, magazine images, logos, information about our brand and how to use our logo, and more, all in one convenient location!";

        return $vw;

    }

    public function getContact()
    {
        $code = array();
        $code[] = View::make('home.jscode.contact');
        $vw = View::make('home.contact')->with('code', implode(' ', $code));
        $vw->title = "Contact Us | SaveOn";
        $vw->description = "Get in touch with SaveOn for information on the discounts and offers we provide.";

        return $vw;

    }

    public function getRedemptionchart()
    {
        $code = array();
        $vw = View::make('home.redemptionchart')->with('code', implode(' ', $code));
        $vw->title = "Redemption Chart | SaveOn";
        $vw->description = "";

        return $vw;

    }

    public function getRedemptionchartrequest()
    {
        $code = array();
        $vw = View::make('home.redemptionchartrequest')->with('code', implode(' ', $code));
        $vw->title = "Redemption Chart | SaveOn";
        $vw->description = "";

        return $vw;

    }

    public function getTerms()
    {
        $code = array();
        $vw = View::make('home.terms')->with('code', implode(' ', $code));
        $vw->title = "Terms of Service | SaveOn";
        $vw->description = "Terms of service and usage for our offers at SaveOn.com";

        return $vw;

    }

    public function getPrivacy()
    {
        $code = array();
        $vw = View::make('home.privacy')->with('code', implode(' ', $code));
        $vw->title = "Privacy";
        $vw->description = "The SaveOn privacy policy. How we can use your information when you use our offers and discounts.";

        return $vw;

    }

    public function getSitemap()
    {
        $code = array();
        $vw = View::make('home.sitemap')->with('code', implode(' ', $code));
        $vw->title = "SaveOn.com Sitemap";
        $vw->description = "SaveOn's Sitemap, where you can navigate your way through our offers, coupons, deals, and discounts.";
        $sohi = $this->featureRepository->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->geoip = json_decode(GeoIp::getGeoIp('json'));

        return $vw;

    }
                
    public function getAdspecs()
    {
        $code = array();
        $vw = View::make('home.adspecs')->with('code', implode(' ', $code));
        $vw->title = "Ad Specs | SaveOn";
        $vw->description = "For information on how to format print ads and print coupons, see the SaveOn advertising specs.";

        return $vw;

    } 

    public function getFeaturedmerchants()
    {
        $code = array();
        $vw = View::make('home.featuredmerchants')->with('code', implode(' ', $code));
        $vw->title = "Featured Merchants | SaveOn";
        $vw->description = "Merchants who love offering deals and coupons with SaveOn";

        return $vw;

    }

    public function getPrintproducts()
    {
        $code = array();
        $vw = View::make('home.printproducts')->with('code', implode(' ', $code));
        $vw->title = "Print Products | SaveOn";
        $vw->description = "SaveOn offers a chance for merchants to reach customers with coupons and deals through SaveOn magazine.";

        return $vw;

    }

    public function getDigitalproducts()
    {
        $code = array();
        $vw = View::make('home.digitalproducts')->with('code', implode(' ', $code));
        $vw->title = "Digital Products | SaveOn";
        $vw->description = "SaveOn's incredible digital platform, SaveOn.com, offers merchants a way to reach consumers with mobile coupons and online deals.";

        return $vw;

    }

    public function getBrands()
    {
        $code = array();
        $vw = View::make('home.brands')->with('code', implode(' ', $code));
        $vw->title = "Our Brands | SaveOn";
        $vw->description = "SaveOn wants you to save money in all areas of life. We have many brands with offers in travel, home imporovement and cars, to name a few.";

        return $vw;

    }

    public function getMaps()
    {
        $code = array();
        $vw = View::make('home.maps')->with('code', implode(' ', $code));
        $vw->title = "Maps | SaveOn";
        $vw->description = "Coverage maps of SaveOn's markets, where consumers can find amazing coupons and deals.";
        return $vw;

    }

    public function getFacebookPrivacy()
    {
        $vw = View::make('home.facebook-privacy')->with('code', '');
        $vw->title = 'Facebook Privacy Policy | SaveOn';
        $vw->description = "The SaveOn Facebook Privacy Policy.";
        return $vw;
    }

    public function getFileupload()
    {
        $code = array();
        $vw = View::make('home.fileupload')->with('code', implode(' ', $code));
        $vw->title = "FTP Upload | SaveOn";
        $vw->description = "Merchants can upload files to SaveOn to facilitate the process of reaching consumers with coupons and deals.";

        return $vw;

    }
    
    public function getWhyadvertise()
    {
        $code = array();
        $vw = View::make('home.whyadvertise')->with('code', implode(' ', $code));
        $vw->title = "Why Advertise | SaveOn";
        $vw->description = "Why do we advertise?";

        return $vw;

    }

    public function getWinnerscircle()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.winnerscircle');
        $vw = View::make('home.winnerscircle')->with('code', implode(' ', $code));
        $vw->title = "Contest Winners Circle | SaveOn";
        $vw->description = "All of SaveOn's lucky contest winners.";
        $vw->geoip = json_decode(GeoIp::getGeoIp('json'));

        return $vw;
    }

    public function getMerchantsurvey()
    {
        $code = array();
        $vw = View::make('home.merchantsurvey')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }


    public function getCityfeature()
    {
        $code = array();
        $vw = View::make('home.cityfeature')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }


    public function getLanding()
    {
        $code = array();
        $code[] = View::make('home.jscode.landing');
        $vw = View::make('home.landing')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postFileupload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_artwork';
        $month = date('M');
        $errors = array();
        $uploaded = array();
        $assets = array();
        
        /*
        inputName
        inputClientContact
        inputPhone
        inputAdvertiser
        inputCity
        inputMonth
        inputSalesRep
        inputEmail
        fileattachment1
        fileattachment2
        fileattachment3
        fileattachment4
        */
        $inputData = Input::all();
        if(empty($inputData['inputCity']))
        {
            $errors["inputCity"] = "Market (City) required.";
        }
        else
        {
            switch($inputData['inputCity'])
            {
                case 'Chicago':
                    $sCity = 'CHI';
                    $aEmailRecipient[] = 'lhoffman@saveoneverything.com';
                    $aEmailRecipient[] = 'art.chicago@saveoneverything.com';
                    break;
                case 'Detroit':
                    $sCity = 'DET';
                    $aEmailRecipient[] = 'kschuck@saveoneverything.com';
                    $aEmailRecipient[] = 'art.detroit@saveoneverything.com';
                    break;
                case 'Minneapolis':
                    $sCity = 'MN';
                    $aEmailRecipient[] = 'lgutzke@saveoneverything.com';
                    $aEmailRecipient[] = 'art.twincities@saveoneverything.com';
                    break;
                case 'Grand Rapids':
                    $sCity = 'GR';
                    $aEmailRecipient[] = 'art.grandrapids@saveon.com';
                    break;
                case 'Lansing':
                    $sCity = 'LAN';
                    $aEmailRecipient[] = 'art.lansing@saveon.com';
                    break;
                case 'Lakeshore':
                    $sCity = 'LK';
                    $aEmailRecipient[] = 'art.lakeshore@saveon.com';
                    break;
                case 'Kalamazoo':
                    $sCity = 'KZ';
                    $aEmailRecipient[] = 'art.kalamazoo@saveon.com';
                    break;
                case 'Toledo':
                    $sCity = 'TOL';
                    $aEmailRecipient[] = 'art.toledo@saveon.com';
                    break;
                case 'Print.MI.GR':
                    $sCity = 'P.MI.GR';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
                case 'Print.MI.LK':
                    $sCity = 'P.MI.LK';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
                case 'Print.MI.KZ':
                    $sCity = 'P.MI.KZ';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
            }
        }

        if(empty($inputData['inputEmail']))
        {
            $errors["inputEmail"] = "Email required.";
        }

        if(isset($sCity) && empty($errors))
        {
            if (Input::hasFile('fileattachment1')) 
            {
                $temp_file = Input::file('fileattachment1')->getRealPath();
                $asset = $month."/".time()."-".Input::file('fileattachment1')->getClientOriginalName();

                $art = new SOE\DB\Artwork;
                $art->name = $inputData["inputName"];
                $art->client_contact = $inputData["inputClientContact"];
                $art->phone = $inputData["inputPhone"];
                $art->advertiser = $inputData["inputAdvertiser"];
                $art->city = $inputData["inputCity"];
                $art->month = $inputData["inputMonth"];
                $art->sales_rep = $inputData["inputSalesRep"];
                $art->email = $inputData["inputEmail"];
                $art->filename = Input::file('fileattachment1')->getClientOriginalName();
                $art->s3_link = "http://s3.amazonaws.com/saveoneverything_artwork/".$asset;

                $s3 = new S3($awsAccessKey, $awsSecretKey);
                // Put our file (also with public read access)
                if ($s3->putObjectFile($temp_file, $bucketName, $asset, S3::ACL_PUBLIC_READ)) 
                {
                    $uploaded[] = array("message" => Input::file('fileattachment1')->getClientOriginalName()." was uploaded successfully!", "name" => Input::file('fileattachment1')->getClientOriginalName(), "status" => "success");
                    $art->status = "success";
                    
                } 
                else 
                {
                    $uploaded[] = array("message" => "There was an error uploading ".Input::file('fileattachment1')->getClientOriginalName(), "name" => Input::file('fileattachment1')->getClientOriginalName(), "status" => "error");
                    $art->status = "error";
                }

                $art->save();
            }   
            if(count($uploaded) == 0)
            {
                $errors["no_files"] = "At least one file is required.";
            }   
        }

        if(isset($aEmailRecipient) && empty($errors))
        {
            $data = array(
                'input_data' => $inputData,
                'uploaded' => $uploaded,
                'link' => "http://s3.amazonaws.com/saveoneverything_artwork/".$asset
            );

            Mail::send('emails.ftpupload', $data, function($message) use($inputData, $aEmailRecipient)
            {
                $message->to($inputData['inputEmail'])->subject('FTP Upload');
                $message->to("art.detroit@saveon.com")->subject('FTP Upload');
                $message->to("kstevens@saveon.com")->subject('FTP Upload');
                foreach($aEmailRecipient as $recip)
                {
                    $message->bcc($recip);
                }
                $message->bcc('wfobbs@saveoneverything.com');
                $message->bcc('cbeery@saveoneverything.com');
            });
        }

        $vw = View::make('home.fileupload');
        $vw->uploaded = $uploaded;
        $vw->error_msgs = $errors;
        return $vw;
    }

    public function postLogin()
    {
        $email = Input::get('signInEmail');
        $password = Input::get('signInPassword');
        $redirect = Input::get('signInRedirect', 'true');
        $data = array();
        if(Input::has('signInEid') && Input::get('signInEid') != '0')
        {
            $data['showeid'] = Input::get('signInEid');
            $data['eidtype'] = strtolower(Input::get('signInType', ''));
        }
        $query = parse_url(URL::previous(), PHP_URL_QUERY);
        parse_str($query, $params);
        if(isset($params['q']) && isset($params['t']))
        {
            $data['q'] = $params['q'];
            $data['t'] = $params['t'];
        }
        if(Auth::attempt(array('email'=>$email,'password'=>$password)))
        {
            if($redirect == 'true')
                return Redirect::to('/members/dashboard');
            else
                return Redirect::to(Input::get('currentUrl').'?'.http_build_query($data));
        }
        else
        {
            $url = Request::header('referer');
            $url = stristr($url, 'signInModal') ? $url : $url.'?modal=signInModal';
            return Redirect::to($url)->with(array('signInError' => true));
        }
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('/');
    }

    public function postSignup()
    {
        $fname = Input::get('signUpFirstName');
        $lname = Input::get('signUpLastName');
        $email = Input::get('signUpEmail');
        $password = Input::get('signUpPassword');
        $month = Input::get('SignUpDateOfBirthMonth');
        $day = Input::get('SignUpDateOfBirthDay');
        $year = Input::get('SignUpDateOfBirthYear');
        $sex = Input::get('signUpGender');
        $zipcode = Input::get('signUpLastZip');
        $signUpSource = Input::get('signUpSource');

        if(Input::has('signUpType') && Input::get('signUpType') != '0')
        {
            $signupSourceID = Input::get('signUpEid');
            $signupSource = Input::get('signUpType');
        }

        $validator = Validator::make(
            Input::all(),
            array('signUpFirstName' => 'required',
                'signUpEmail' => 'required|email|unique:users,email',
                'signUpPassword' => 'required'
                )
        );
        if($validator->fails())
        {
            return Redirect::to('/');
        }

        $user = $this->userRepository->blank();
        $user->name = $fname.' '.$lname;
        $user->email = $email;
        $user->password = $password;
        $user->sex = $sex;
        $user->birthday = date('Y-m-d H:i:s', strtotime($year.'-'.$month.'-'.$day));
        $user->is_email_valid = 0;
        $user->signup_reference = $signUpSource;
        $birthday = new DateTime($user->birthday);
        $interval = $birthday->diff(new DateTime, true);
        $user->age = $interval->format('%y');
        $user->zipcode = $zipcode;
        if (isset($signupSource)) {
            $user->signup_source = $signupSource;
            $user->signup_source_id = $signupSourceID;
        }
        $zip = $this->zipcodeRepository->findByZipcode($zipcode);
        if(!empty($zip))
        {
            $user->latitude = $zip->latitude;
            $user->longitude = $zip->longitude;
            $user->latm = $zip->latm;
            $user->lngm = $zip->lngm;
            $user->city = $zip->city;
            $user->state = $zip->state;
        }
        $user->save();

        //Auth::attempt(array('email'=>$email,'password'=>$password));
        Auth::login($user);
        $redirect = Input::get('signUpRedirect', 'true');
        $data = array();
        if(Input::has('signUpEid') && Input::get('signUpEid') != '0')
        {
            $data['showeid'] = Input::get('signUpEid');
            $data['eidtype'] = strtolower(Input::get('signUpType',''));
        }
        $query = parse_url(URL::previous(), PHP_URL_QUERY);
        parse_str($query, $params);
        if(isset($params['q']) && isset($params['t']))
        {
            $data['q'] = $params['q'];
            $data['t'] = $params['t'];
        }

        $identity = $user->email;
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify($identity);
        $mp->track('Signup', array(
            '$city' => $this->geoip->city_name,
            '$region' => $this->geoip->region_name,
            'SignupType' => strtolower(Input::get('signUpType','')),
            'SignupSourceID' => Input::get('signUpEid', 0),
            'Environment' => \App::environment()
        ));
        
        if($redirect == 'true')
                return Redirect::to('/');
            else
                return Redirect::to(Input::get('currentUrl').'?'.http_build_query($data));

    }

    public function postChangeLocation()
    {
        $lat = Input::get('latitude');
        $lng = Input::get('longitude');
        $city = Input::get('city');
        $state = Input::get('state');
        $url = Input::get('url');
        //$user = Auth::check() ? Auth::User() : Auth::Nonmember();
        //UserLocation::setLocation($user, $lat, $lng, $city, $state);
        $this->userLocations->setLocation(Auth::person(), $lat, $lng, $city, $state);

        $parts = parse_url($url);
        if(isset($parts['query']))
        {
            parse_str($parts['query'], $query);
            if(!isset($query['chg']))
                $query['chg'] = 1;
        }
        else
        {
            $query = array('chg' => 1);
        }

        return Redirect::to($parts['path'].'?'.http_build_query($query));
    }

    /**
     * postUpdateLocation changes location based on browser-supplied lng-lat
     */
    public function postUpdateLocation()
    {
        $lat = Input::get('latitude');
        $lng = Input::get('longitude');

        $location = $this->userLocations->setLocation(Auth::person(), $lat, $lng);

        $cartesian = SoeHelper::getCartesian($location['latitude'], $location['longitude']);
        $city_image = SOE\DB\CityImage::where('state', '=', $location['region_name'])
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));

        $location['city_image'] = $city_image ? $city_image->path : '';

        return $location;
    }

    public function getPrintExternal($entity_id)
    {
        $entity = Entity::find($entity_id);
        if(empty($entity))
            return Redirect::to('/');

        $user = Auth::check() ? Auth::User() : Auth::Nonmember();
        $userRep = Auth::check() ? User::createFromModel($user) : Nonmember::createFromModel($user);
        $userRep->printEntity($entity);
        $url = $entity->print_override != '' ? $entity->print_override : $entity->url;
        return Redirect::to($url);
    }

    public function getMerchantBannerClick($banner_entity_id = null)
    {
        if(!$banner_entity_id)
            return Redirect::to('/');
        $banner = $this->bannerEntities->find($banner_entity_id);
        if(!$banner)
            App::abort(404);
        $person = $this->personFactory->make();
        $clickable = $this->clickableFactory->make('BannerEntity', $banner_entity_id);
        if($person && $clickable)
            $clickable->click($person);
        return Redirect::to(\Input::get('redirect'));
    }

    public function getMerchantWebsiteClick($location_id = null)
    {
        if(!$location_id)
            return Redirect::to('/');
        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make('Website', $location_id);
        if($person && $viewable)
            $viewable->view($person);
        $location = $this->locations->find($location_id);
        if(!$location)
            App::abort(404);
        return Redirect::to((strpos($location->website, 'http') === false) ? 'http://'.$location->website : $location->website);
    }

    public function getMerchantCustomWebsiteClick($location_id = null)
    {
        if(!$location_id)
            return Redirect::to('/');
        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make('Website|custom', $location_id);
        if($person && $viewable)
            $viewable->view($person);
        $location = $this->locations->find($location_id);
        if(!$location)
            App::abort(404);
        return Redirect::to((strpos($location->custom_website, 'http') === false) ? 'http://'.$location->custom_website : $location->custom_website);
    }

    public function getTest()
    {
        
    }

    public function getThirtyyears()
    {
        $code = array();
        $vw = View::make('home.thirtyyears')->with('code', implode(' ', $code));
        $vw->title = "30 Years of Savings | SaveOn";
        $vw->description = "SaveOn is celebrating 30 years of saving customers money.  Browse our fantastic offers, and enter our contest to win a new car! You can also win five thousand dollars by becoming a member.";

        return $vw;

    }

    /*public function getHomeimprovement()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.homeimprovement');
        $vw = View::make('home.homeimprovement')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Home Improvement";
        $vw->description = "SaveOn Home Improvement aims to save you money on your next project or renovation by offering leads for contractors.  It is our aim to get you the best deals possible from our Save Certified home improvement professionals.";

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $city_image = SOE\DB\CityImage::where('state', '=', $geoip->region_name)
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $vw->city_image = $city_image;
        $vw->geoip = $geoip;

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $city_image = SOE\DB\CityImage::where('state', '=', $geoip->region_name)
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $vw->city_image = $city_image;
        $vw->geoip = $geoip;

        return $vw;

    }*/

    public function getGroceries()
    {
        $code = array();
        $code[] = View::make('home.jscode.groceries');
        $vw = View::make('home.groceries')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Groceries";
        $vw->description = "SaveOn Groceries offers great grocery coupons and deals to maximize your savings at the grocery store";

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $user = Auth::check() ? Auth::User() : false;
        $show_demo = !$user ? false : $this->userRepository->showDemo($user);
        $category_ads = $this->advertisements->search('merchant', 'basic', 'menu', null, 0, 0, $show_demo, 0, 1);
        if($category_ads['stats']['returned'] != 0)
        {
            $person = $this->personFactory->make();
            $viewable = $this->viewableFactory->make('Advertisement', $category_ads['objects'][0]->id);
            if($person && $viewable)
                $viewable->view($person);
        }
        $vw->geoip = $this->geoip;
        $vw->category_ads = $category_ads;

        return $vw;
    }

    public function getVegas()
    {
        $domain = $_SERVER['HTTP_HOST'];
        if (strpos($domain, 'saveon.com') !== FALSE)
        {
            return Redirect::to('/');
        }

        $code = array();
        $code[] = View::make('home.jscode.vegas');
        $vw = View::make('home.vegas')->with('code', implode(' ', $code));
        $vw->title = "Vegas Vacation";
        $vw->description = "";

        return $vw;
        
    }

    public function getMobile()
    {
        $code = array();
        $vw = View::make('home.mobile')->with('code', implode(' ', $code));
        $vw->title = "SaveOn is Now On Your Phone";
        $vw->description = "SaveOn is now on your mobile phone. Access your coupons everywhere.";

        return $vw;
    }

    public function getTakeYourCouponsWithYou()
    {
        $code = array();
        $vw = View::make('home.take-your-coupons-with-you')->with('code', implode(' ', $code));
        $vw->title = "Take Your Coupons with You";
        $vw->description = "Save your coupons and bring your coupons with you.";

        return $vw;
    }

    public function getMemberBenefits()
    {
        if (Auth::check())
        {
            return Redirect::to('/members/mystuff');
        }
        $code = array();
        $vw = View::make('home.member-benefits')->with('code', implode(' ', $code));
        $vw->title = "Member Benefits";
        $vw->description = "Being a Member has Benefits";

        return $vw;
    }

    public function getCommunity($giveback = 'giveback', $two = null, $three = null)
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $vw = View::make('home.community')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Gives Back";
        $vw->description = "SaveOn Gives Back";

        return $vw;
    }

    public function getMskSurvey()
    {
        $code = array();
        $vw = View::make('home.msk-survey')->with('code', implode(' ', $code));
        $vw->title = "Merchant Starter Kit Survey";
        $vw->description = "Merchant Starter Kit Survey";

        return $vw;
    }

    public function getSponsor($district_slug)
    {
        $district = $this->districts->findBySlug($district_slug);
        if(!$district)
            return Redirect::to('/');
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.sponsor');
        $vw = View::make('home.sponsor')->with('code', implode(' ', $code));
        $vw->title = "Restaurants that Sponsor ".$district->name;
        $vw->description = "";
        $geoip = $this->geoip;

        $results = $this->entities->getSponsors($district_slug, 'nearest', Input::get('page', 0), 12);
        $user = Auth::check() ? Auth::User() : false;
        if(!empty($user))
            $results['objects'] = $this->userRepository->markClipped($user, $results['objects']);

        $vw->entities = $results['objects'];
        $vw->geoip = $geoip;
        $vw->district_slug = $district_slug;
        $vw->district = $district;
        $vw->user_id = $user ? $user->id : 0;

        $vw->banner = $this->franchises->getSponsorBanner($district_slug);

        return $vw;
    }

    public function getChamber()
    {
        $code = array();
        $vw = View::make('home.chamber')->with('code', implode(' ', $code));

        $vw->title = "Your Chamber of Commerce and SaveOn";
        $vw->description = "We are offering a limited time promotion to advertise with us for six months, at no cost to you!";

        return $vw;
    }

    public function postSendChamberInfo()
    {
        $firstname = Input::get('firstname');
        $lastname = Input::get('lastname');
        $company = Input::get('company');
        $phone = Input::get('phone');
        $email = Input::get('email');
        $data = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'company' => $company,
            'phone' => $phone,
            'email' => $email
        );

        Mail::send('emails.chamber-email', $data, function($message)
        {
            $message->to('abedor@saveon.com', 'Aaron Bedor')->to('cmelie@saveon.com', 'Colleen Melie')->subject('Chamber of Commerce Contact Information');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
        return Redirect::to('chamber')->with(array('contact_send' => 'true'));
    }

    public function getEvents($slug)
    {
        $event = $this->companyEvents->getBySlug($slug);
        if (!$event)
            return Redirect::to('/');

        $code = array();
        $vw = View::make('home.events')->with('code', implode(' ', $code));

        $vw->title = "Sign Up for ".$event->name;
        $vw->description = "Sign Up to attend ".$event->name." hosted by SaveOn.";

        $vw->event = $event;

        return $vw;
    }

    public function postEventsSubmit($event_id)
    {
        $event = $this->companyEvents->find($event_id);
        if (!$event)
            return Redirect::to('/');

        $validator = Validator::make(
            Input::all(),
            array('attendee_name' => 'required',
                'attendee_email' => 'required|email',
                'attendee_company' => 'required'
                )
        );
        if($validator->fails())
        {
            return Redirect::to('events/'.$event->slug)->withInput()->withErrors($validator);
        }

        $attendee_name = Input::get('attendee_name');
        $attendee_email = Input::get('attendee_email');
        $attendee_company = Input::get('attendee_company');

        $attendee = $this->companyEventAttendees->blank();
        $attendee->name = $attendee_name;
        $attendee->email = $attendee_email;
        $attendee->company = $attendee_company;
        $attendee->company_event_id = $event->id;
        $attendee->save();

        $data = array(
            'attendee_name' => $attendee_name,
            'attendee_email' => $attendee_email,
            'attendee_company' => $attendee_company,
            'event' => $event
        );

        Mail::send('emails.event-signup', $data, function($message) use ($attendee_name, $attendee_email)
        {
            $message->to($attendee_email, $attendee_name)->subject('Thank You for Signing Up');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });

        return Redirect::to('events/'.$event->slug)->with(array('attendee_submit' => 'true'));
    }

    public function getCommercials()
    {
        $code = array();
        $code[] = View::make('home.jscode.commercials');
        $vw = View::make('home.commercials')->with('code', implode(' ', $code));

        $vw->title = "SaveOn Commercials";
        $vw->description = "Watch our commercials throughout all of our markets.";

        return $vw;
    }

    //White Label
    /*public function getPartner($company_id=null)
    {
        $company = SOE\DB\Company::where('id', '=', $company_id)->first();
        if(!empty($company))
        {
            $code = array();
            $code[] = View::make('home.jscode.masonry');
            $code[] = View::make('home.jscode.index');
            $vw = View::make('home.index')->with('code', implode(' ', $code));
            $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
            $vw->description = "SaveOn";

            //$geoip = json_decode(GeoIp::getGeoIp('json'));
            $cartesian = SoeHelper::getCartesian($company->latitude, $company->longitude);
            $city_image = SOE\DB\CityImage::where('region_type', '=', 'City')
                ->orderBy('distance', 'asc')
                ->remember(600)
                ->first(array(
                    'city_images.*',
                    DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
            $vw->city_image = $city_image;
            $sohi = $this->featureRepository->findByName('home_improvement');
            $vw->sohi = empty($sohi) ? 0 : $sohi->value;
            $vw->company = $company;
            return $vw;
        } else {
            return Redirect::to('/');
        }
    }*/

}

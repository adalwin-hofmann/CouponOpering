<?php

class EmailController extends BaseController {

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
        \ContestRepositoryInterface $contests,
        \UserRepositoryInterface $userRepository,
        \FeatureRepositoryInterface $features,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants,
        \NewsletterRepositoryInterface $newsletters,
        \ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->contests = $contests;
        $this->userRepository = $userRepository;
        $this->features = $features;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->newsletters = $newsletters;
        $this->zipcodes = $zipcodes;
    }

/**
*This function retrieves the index page.
*/
    public function getPasswordreset()
    {
        $code = array();
        $vw = View::make('emails.passwordreset')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getValidate()
    {
        $code = array();
        $username = Auth::check() ? Auth::User()->name : 'John Doe';
        $vw = View::make('emails.validate')->with(array('code' => implode(' ', $code), 'username' => $username));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getWelcome()
    {
        $code = array();
        $username = Auth::check() ? Auth::User()->name : 'John Doe';
        $vw = View::make('emails.welcome')->with(array('code' => implode(' ', $code), 'username' => $username));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getWelcometour()
    {
        $code = array();
        $username = Auth::check() ? Auth::User()->name : '';
        $vw = View::make('emails.welcometour')->with(array('code' => implode(' ', $code), 'username' => $username));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getNewsletter()
    {
        $code = array();
        $vw = View::make('emails.newsletter')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getNewsletteremailtest()
    {
        if(!Auth::check())
            return Redirect::to('/');
        $member = Auth::User();

        /********************/
        $geoip = new \StdClass;
        $geoip->region_name = $member->state;
        $geoip->city_name = $member->city;
        $geoip->latitude = $member->latitude;
        $geoip->longitude = $member->longitude;

        $history = array('coupons' => [], 'deals' => [], 'contests' => []);//$this->newsletters->getMemberHistory($schedule->batch_id, $member->email, 2);

        $coupons = $this->userRepository->getRecommendations($member, 16, $geoip, 'score', 'soe', 0, 'coupon', true, $history['coupons']);
        $deals = $this->userRepository->getRecommendations($member, 2, $geoip, 'score', 'soe', 0, 'dailydeal', true, $history['deals']);
        $contests = $this->userRepository->getRecommendations($member, 2, $geoip, 'score', 'soe', 0, 'contest', true, $history['contests']);

        /* Custom Featured Queries */
        /*$featuredLocation = $this->merchants->findNearestLocationByMerchantIdWithin50($schedule->featured_merchant_id, $geoip->latitude, $geoip->longitude);
        if($featuredLocation)
        {
            $featured = $this->locations->getFeaturedByLocation($featuredLocation, true);
        } else {
            $featured = 0;
        }*/
        $featuredLocation = $this->merchants->findNearestLocationBySlugWithin50('goodwill-industries-of-greater-detroit', $this->geoip->latitude, $this->geoip->longitude);
        if($featuredLocation)
        {
            $featured = $this->locations->getFeaturedByLocation($featuredLocation, true);
        } else {
            $featured = 0;
        }

        $data = array('coupons' => array(), 'deals' => array(), 'contests' => array(), 'featured' => array());
        if(count($coupons['objects']) % 3 != 0 && count($coupons['objects'] > 0))
            $coupons['objects'] = array_slice($coupons['objects'], 0, count($coupons['objects']) - count($coupons['objects']) % 3);
        foreach($coupons['objects'] as $coupon)
        {
            $data['coupons'][] = $coupon->toArray();
        }
        if(count($deals['objects']) < 2)
        {
            foreach($deals['objects'] as $deal)
            {
                $data['deals'][] = $deal->toArray();
            }
        }
        foreach($contests['objects'] as $contest)
        {
            $c = $this->contests->find($contest->entitiable_id);
            $aContest = $contest->toArray();
            $aContest['display_name'] = $c->display_name;
            $data['contests'][] = $aContest;
        }
        if(is_object($featured))
        {
            $data['featured'] = $featured->toArray();
        }
        $data = json_encode($data);
        $data = json_decode($data, true);
        $intro = $this->features->findByName('member_newsletter_intro', false);
        $intro = ($intro ? $intro->value : 'Hi SaveOn Member! We’ve done a little homework and found a few of our favorite deals just for you...');
        //$intro = $schedule->intro_paragraph != '' ? $schedule->intro_paragraph : ($intro ? $intro->value : 'Hi SaveOn Member! We’ve done a little homework and found a few of our favorite deals just for you...');
        $email_data = array(
            'coupons' => $data['coupons'],
            'deals' => $data['deals'],
            'contests' => $data['contests'],
            'featured' => $data['featured'],
            'intro' => $intro
        );

        $code = array();
        $vw = View::make('emails.newsletter')->with('code', implode(' ', $code));
        $vw->coupons = $data['coupons'];
        $vw->deals = $data['deals'];
        $vw->contests = $data['contests'];
        $vw->featured = $data['featured'];
        $vw->intro = $intro;

        return $vw;
    }

    public function getDeleteaccount()
    {
        $code = array();
        $vw = View::make('emails.deleteaccount')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getMerchantreport()
    {
        $code = array();
        $vw = View::make('emails.merchantreport')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSharing()
    {
        $code = array();
        $vw = View::make('emails.sharing')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getContestended()
    {
        $code = array();
        $vw = View::make('emails.contestended')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSavedsavetoday()
    {
        $code = array();
        $vw = View::make('emails.savedsavetoday')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSavedcoupon()
    {
        $code = array();
        $vw = View::make('emails.savedcoupon')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getUnredeemedcoupons()
    {
        $code = array();
        $vw = View::make('emails.unredeemedcoupons')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getFavoritemerchant()
    {
        $code = array();
        $vw = View::make('emails.favoritemerchant')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getRedemptionchart()
    {
        $code = array();
        $vw = View::make('emails.redemptionchart')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }


    public function getSalesreport()
    {
        $code = array();
        $vw = View::make('emails.salesreport')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSohifreetrial()
    {
        $code = array();
        $vw = View::make('emails.sohifreetrial')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }
    public function getTrialwarningweek()
    {
        $code = array();
        $vw = View::make('emails.trialwarningweek')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }
    public function getTrialwarningday()
    {
        $code = array();
        $vw = View::make('emails.trialwarningday')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }


    public function getCorporatenewsletter()
    {
        $code = array();
        $vw = View::make('emails.corporatenewsletter')->with('code', implode(' ', $code));
        //$vw = View::make('emails.christmascorporatenewsletter')->with('code', implode(' ', $code));
        $vw->title = "Corporate Newsletter - SaveOn Scoop";
        $vw->description = "Corporate Newsletter - SaveOn Scoop";

        return $vw;
    }

    public function getCorporatenewsletteroops()
    {
        $code = array();
        $vw = View::make('emails.corporatenewsletteroops')->with('code', implode(' ', $code));
        $vw->title = "Corporate Newsletter - SaveOn Scoop";
        $vw->description = "Corporate Newsletter - SaveOn Scoop";

        return $vw;
    }

    public function getSohiDrip1()
    {
        $code = array();
        $vw = View::make('emails.sohi.sohi-drip1')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSohiDrip2()
    {
        $code = array();
        $vw = View::make('emails.sohi.sohi-drip2')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getSohiDrip3()
    {
        $code = array();
        $vw = View::make('emails.sohi.sohi-drip3')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function getLeadreport()
    {
        $code = array();
        $vw = View::make('emails.sohi.leadreport')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    /* Merchant Starter Kit */
    public function getMskWelcome()
    {
        $code = array();
        $vw = View::make('emails.msk-welcome')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Welcome Aboard!";
        $vw->description = "SaveOn - Welcome Aboard!";

        return $vw;
    }
    public function getMskWelcomeEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-welcome', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Welcome Aboard!');
        });
    }
    public function getMskMobile()
    {
        $code = array();
        $vw = View::make('emails.msk-mobile')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Your Mobile Microsite";
        $vw->description = "SaveOn - Your Mobile Microsite";

        return $vw;
    }
    public function getMskMobileEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-mobile', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Your Mobile Microsite');
        });
    }
    public function getMskShare()
    {
        $code = array();
        $vw = View::make('emails.msk-share')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Make the Most of Your Microsite";
        $vw->description = "SaveOn - Make the Most of Your Microsite";

        return $vw;
    }
    public function getMskShareEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-share', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Make the Most of Your Microsite');
        });
    }
    public function getMskLearnmore()
    {
        $code = array();
        $vw = View::make('emails.msk-learnmore')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Utilizing Daily Deals &amp; Contests";
        $vw->description = "SaveOn - Utilizing Daily Deals &amp; Contests";

        return $vw;
    }
    public function getMskLearnmoreEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-learnmore', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Utilizing Daily Deals &amp; Contests');
        });
    }
    public function getMskSurvey()
    {
        $code = array();
        $vw = View::make('emails.msk-survey')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Welcome Aboard!";
        $vw->description = "SaveOn - Make the Most of Your Microsite!";

        return $vw;
    }
    public function getMskSurveyEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-survey', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Make the Most of Your Microsite!');
        });
    }
    public function getMskLetter()
    {
        $code = array();
        $vw = View::make('emails.msk-letter')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Welcome Aboard!";
        $vw->description = "";

        return $vw;
    }
    public function getMskGrLetter()
    {
        $code = array();
        $vw = View::make('emails.msk-gr-letter')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Welcome Aboard!";
        $vw->description = "";

        return $vw;
    }
    public function getMskLetterEmail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.msk-letter', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - Welcome Aboard!');
        });
    }

    public function getSuggestion()
    {
        $suggestion = \SOE\DB\Suggestion::find(1);
        $aEmails = array('cjbeery@gmail.com');
        Mail::send('emails.suggestion', array('suggestion' => $suggestion), function($message) use ($aEmails)
        {
            foreach($aEmails as $email)
            {
                $message->to($email);
            }
            $message->subject('User Suggestion');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
    }

    /* Merchant Portal */
    public function getMerchantPortalInvite()
    {
        $code = array();
        $vw = View::make('emails.merchant-portal-invite')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Merchant Portal";
        $vw->description = "";

        return $vw;
    }
    
    // **********************************

    public function getPasswordresetemail()
    {
        $email = Input::get('email');
        $recovery = $this->userRepository->setPasswordRecovery($email);
        if(empty($recovery))
            return;
        $data = array(
            'key' => $recovery['uniq'],
            'timer' => $recovery['timer'],
        );
        
        Mail::queueOn('SOE_Tasks', 'emails.passwordreset', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Reset Your Password');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
    }

    public function getValidateemail()
    {

        $email = Input::get('email');
        $name = Input::get('name');
        /*$username = Auth::check() ? Auth::User()->name : 'John Doe';*/
        $data = array(
            'email' => $email,
            'name' => $name,
            'username' => $username
        );

        Mail::send('emails.validate', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Welcome Aboard');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
    }

    public function getWelcomeemail()
    {

        $email = Input::get('email');
        $name = Input::get('name');
        /*$username = Auth::check() ? Auth::User()->name : 'John Doe';*/
        $data = array(
            'email' => $email,
            'name' => $name,
            'username' => $username
        );

        Mail::send('emails.welcome', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Welcome To SaveOn');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
    }

    public function getCertifiedEmail()
    {
        Mail::send('emails.certified-email', [], function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn - SAVE Certified!');
        });
    }

    public function getNewsletteremail()
    {
        if(!Auth::check())
            return Redirect::to('/');
        $member = Auth::User();
        $geoip = new \StdClass;
        $geoip->region_name = $member->state;
        $geoip->city_name = $member->city;
        $geoip->latitude = $member->latitude;
        $geoip->longitude = $member->longitude;
        $coupons = $this->userRepository->getRecommendations($member, 10, $geoip, 'rand', 'soe', 0, 'coupon', true);
        //$deals = $this->userRepository->getRecommendations($member, 2, $geoip, 'rand', 'soe', 0, 'dailydeal', true);
        $contests = $this->userRepository->getRecommendations($member, 2, $geoip, 'rand', 'soe', 0, 'contest', true);

        /* Custom Featured Queries */
        /* Will Need Cleanup */
        $featuredLocation = $this->merchants->findNearestLocationBySlugWithin50('goodwill-industries-of-greater-detroit', $geoip->latitude, $geoip->longitude);
        if($featuredLocation)
        {
            $featured = $this->locations->getFeaturedByLocation($featuredLocation, true);
        } else {
            $featured = 0;
        }

        $deals['objects'] = [];

        $data = array('coupons' => array(), 'deals' => array(), 'contests' => array(), 'featured' => array());
        if(count($coupons['objects']) % 2 != 0 && count($coupons['objects'] > 0))
                $coupons['objects'] = array_slice($coupons['objects'], 0, count($coupons['objects']) - 1);
            foreach($coupons['objects'] as $coupon)
            {
                $data['coupons'][] = $coupon->toArray();
            }
            if(count($deals['objects']) < 2)
            {
                foreach($deals['objects'] as $deal)
                {
                    $data['deals'][] = $deal->toArray();
                }
            }
            
        foreach($contests['objects'] as $contest)
        {
            $c = $this->contests->find($contest->entitiable_id);
            $aContest = $contest->toArray();
            $aContest['display_name'] = $c->display_name;
            $data['contests'][] = $aContest;
        }
        
        if(is_object($featured))
        {
            $data['featured'] = $featured->toArray();
        }
        $intro = $this->features->findByName('member_newsletter_intro', false);
        $intro = $intro ? $intro->value : 'Hi SaveOn Member! We’ve done a little homework and found a few of our favorite deals just for you...';
        $data = json_encode($data);
        $data = json_decode($data, true);
        $email_data = array(
            'coupons' => $data['coupons'],
            'deals' => $data['deals'],
            'contests' => $data['contests'],
            'featured' => $data['featured'],
            'intro' => $intro
        );
        $subject = $this->features->findByName('member_newsletter_subject', false);
        $subject = $subject ? $subject->value : 'SaveOn Local Deals Just For You';

        Mail::send('emails.newsletter', $email_data, function($message) use ($subject)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject($subject);
            $message->sender('info@saveon.com', 'SaveOn');
        });
    }

    public function getDeleteaccountemail()
    {

        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.deleteaccount', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('We\'ll Miss You');
        });
    }

    public function getMerchantreportemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.merchantreport', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Weekly Report');
        });
    }

    public function getSohifreetrialemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.sohifreetrial', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('SaveOn Home Improvement Free Trial');
        });
    }

    public function getTrialwarningweekemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.trialwarningweek', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Your Free Trial is Almost Over!');
        });
    }

    public function getTrialwarningdayemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.trialwarningday', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Your Free Trial is Almost Over!');
        });
    }

    public function getSharingemail()
    {

        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.sharing', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Shared Coupon from Save On');
        });
    }

    public function getContestendedemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.contestended', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Contest Ended');
        });
    }

    public function getCorporatenewsletteremail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.corporatenewsletter', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Corporate Newsletter - SaveOn Scoop');
        });
    }

    public function getCorporatenewsletteroopsemail()
    {
        $email = Input::get('email');
        $name = Input::get('name');
        $data = array(
            'email' => $email,
            'name' => $name
        );
        
        Mail::send('emails.corporatenewsletteroops', $data, function($message)
        {
            $message->to(Input::get('email'), Input::get('name'))->subject('Corporate Newsletter - SaveOn Scoop');
        });
    }

    public function getCorporateNewsletterSend()
    {
        if(!Auth::check())
        {
            echo "You're not logged in.";
            return;
        }
        $user = Auth::User();
        if(!$this->userRepository->checkType($user, 'Employee'))
        {
            echo "You're not an employee.";
            return;
        }
        $emails = Input::get('emails', null);
        $emails = !empty($emails) ? explode(',', $emails) : array();
        
        if(count($emails))
        {
            foreach($emails as $email)
            {
                $data = array(
                    'email' => $email,
                    'name' => ''
                );
                $name = '';
                Mail::queueOn('SOE_Tasks', 'emails.corporatenewsletter', $data, function($message) use ($email, $name)
                {
                    $message->from('info@saveon.com', 'SaveOn');
                    $message->to($email, $name)->subject('Corporate Newsletter - SaveOn Scoop');
                    $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"category":"corporate-newsletter"}');
                });
            }
        }
        else
        {
            $all = Input::get('all', null); // Hint: ?all=true
            if($all)
            {
                $users = $this->userRepository->getEmployees();
                foreach($users as $user)
                {
                    $data = array(
                        'email' => $user->email,
                        'name' => $user->name
                    );
                    $email = $user->email;
                    $name = $user->name;
                    Mail::queueOn('SOE_Tasks', 'emails.corporatenewsletter', $data, function($message) use ($email, $name)
                    {
                        $message->from('info@saveon.com', 'SaveOn');
                        $message->to($email, $name)->subject('Corporate Newsletter - SaveOn Scoop');
                        $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"category":"corporate-newsletter"}');
                    });
                }
            }
        }
    }

    public function getCorporateNewsletterOopsSend()
    {
        if(!Auth::check())
            return;
        $user = Auth::User();
        if(!$this->userRepository->checkType($user, 'Employee'))
            return;
        $emails = Input::get('emails', null);
        $emails = !empty($emails) ? explode(',', $emails) : array();
        
        if(count($emails))
        {
            foreach($emails as $email)
            {
                $data = array(
                    'email' => $email,
                    'name' => ''
                );
                $name = '';
                Mail::queueOn('SOE_Tasks', 'emails.corporatenewsletteroops', $data, function($message) use ($email, $name)
                {
                    $message->from('info@saveon.com', 'SaveOn');
                    $message->to($email, $name)->subject('Corporate Newsletter - SaveOn Scoop');
                    $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"category":"corporate-newsletter"}');
                });
            }
        }
        else
        {
            $all = Input::get('all', null);
            if($all)
            {
                $users = $this->userRepository->getEmployees();
                foreach($users as $user)
                {
                    $data = array(
                        'email' => $user->email,
                        'name' => $user->name
                    );
                    $email = $user->email;
                    $name = $user->name;
                    Mail::queueOn('SOE_Tasks', 'emails.corporatenewsletteroops', $data, function($message) use ($email, $name)
                    {
                        $message->from('info@saveon.com', 'SaveOn');
                        $message->to($email, $name)->subject('Corporate Newsletter - SaveOn Scoop');
                        $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"category":"corporate-newsletter"}');
                    });
                }
            }
        }
    }

}
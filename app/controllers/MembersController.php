<?php

class MembersController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */

    protected $contestRepository;
    protected $featureRepository;
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
        ContestRepositoryInterface $contestRepository,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $featureRepository,
        LocationRepositoryInterface $locations,
        UserRepositoryInterface $userRepository
    )
    {
        $this->appEmail = App::make('AppEmailInterface');
        $this->contestRepository = $contestRepository;
        $this->entities = $entities;
        $this->featureRepository = $featureRepository;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->locations = $locations;
        $this->userRepository = $userRepository;
        $this->beforeFilter(function()
        {
            if(!Auth::check())
                return Redirect::to('/');
        });
        parent::__construct();
    }

    public function getIndex()
    {
        return Redirect::to('/members/dashboard');
    }

    public function getDashboard()
    {
        $code = array();
        $code[] = View::make('home.jscode.tour');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.dashboard');
        $vw = View::make('home.dashboard')->with('code', implode(' ', $code));
        $vw->title = "My Dashboard";
        $vw->description = "Your home at SaveOn.com, which hosts your coupons, contests, and daily deals, as well as recommended offers.";

        $geoip = json_decode(GeoIp::getGeoIp('json'));
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

        $sohi = $this->featureRepository->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->page = 'dashboard';
        $vw->geoip = json_decode(GeoIp::getGeoIp('json'));

        return $vw;
    }

    public function getMystuff()
    {
        $code = array();
        //$code[] = View::make('home.jscode.mylocations');
        $vw = View::make('home.mystuff')->with('code', implode(' ', $code));
        $vw->title = "My Stuff";
        $vw->description = "Find all of your stuff here.";
        $vw->page = '';
        $vw->subpage = '';

        return $vw;

    }

    public function getMycoupons()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.mycoupons');
        $vw = View::make('home.mycoupons')->with('code', implode(' ', $code));
        $vw->title = "My Coupons";
        $vw->description = "Where your stored Save On coupons live.";
        $vw->page = 'coupons';

        return $vw;
    }

     public function getMysavetodays()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.mysavetodays');
        $vw = View::make('home.mysavetodays')->with('code', implode(' ', $code));
        $vw->title = "My Save Todays";
        $vw->description = "Where your save todays, also known as Save On daily deals, live.";
        $vw->page = 'savetodays';

        return $vw;
    }

    public function getMycontestentries()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.mycontestentries');
        $vw = View::make('home.mycontestentries')->with('code', implode(' ', $code));
        $vw->title = "My Saved Contest Entries";
        $vw->description = "Save On contest offers you have entered to win.";

        $user = User::createFromModel(Auth::User());
        $vw->enteredContests = $user->getEnteredContests();
        $vw->expiredContests = $user->getExpiredEnteredContests();
        $vw->page = 'contests';

        return $vw;
    }

    public function getMyfavoritemerchants()
    {
        $code = array();
        $code[] = View::make('home.jscode.myfavoritemerchants');
        $vw = View::make('home.myfavoritemerchants')->with('code', implode(' ', $code));
        $vw->title = "My Favorite Merchants";
        $vw->description = "Your favorite Save On merchants with the best offers.";
        $vw->page = 'favoritemerchants';
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getMycars()
    {
        $code = array();
        $code[] = View::make('home.jscode.mycars');
        $code[] = View::make('home.jscode.masonry');
        $vw = View::make('home.mycars')->with('code', implode(' ', $code));
        $vw->title = "My Cars";
        $vw->description = "Your favorite Save On Vehicles.";
        $vw->page = 'cars';

        return $vw;
    }

    public function getMysettings()
    {
        $code = array();
        $code[] = View::make('home.jscode.mysettings');
        $vw = View::make('home.mysettings')->with('code', implode(' ', $code));
        $vw->title = "My Settings";
        $vw->description = "Save On user settings, so SaveOn.com offers a site customized for you.";
        $vw->user = Auth::User();
        $vw->page = 'settings';
        $vw->subpage = 'settings';

        return $vw;

    }

    public function getMyinterests()
    {
        $code = array();
        $code[] = View::make('home.jscode.myinterests');
        $vw = View::make('home.myinterests')->with('code', implode(' ', $code));
        $vw->title = "My Interests";
        $vw->description = "Save On wants to recommend offers that interest you.";
        $vw->user = Auth::User();
        $vw->page = 'interests';
        $vw->subpage = 'settings';

        return $vw;
    }

    public function postSavePreferences()
    {
        $user = Auth::User();
        $aPreferences = array("food_dining", "home_services", "health_beauty", "auto_transportation", "travel_entertainment", "retail_fashion", "special_services");
        foreach($aPreferences as $preference)
        {
            if(Input::has($preference))
            {
                $pref = $preference.'_preference';
                $user->$pref = 1;
            }
            else
            {
                $pref = $preference.'_preference';
                $user->$pref = 0;
            }
        }
        $user->save();
        return Redirect::to('members/myinterests')->with(array('settings_saved' => 'true'));
    }

    public function getMylocations()
    {
        $code = array();
        $code[] = View::make('home.jscode.mylocations');
        $vw = View::make('home.mylocations')->with('code', implode(' ', $code));
        $vw->title = "My Locations";
        $vw->description = "Save On wants to recommend offers in your area. ";
        $vw->page = 'locations';
        $vw->subpage = 'settings';

        return $vw;

    }

    public function getMynotifications()
    {
        $code = array();
        $code[] = View::make('home.jscode.mynotifications');
        $vw = View::make('home.mynotifications')->with('code', implode(' ', $code));
        $vw->title = "My Notifications";
        $vw->description = "Pick when you want to hear from Save On about discounts.";
        $vw->page = 'notifications';
        $vw->subpage = 'settings';

        return $vw;

    }

    public function postSaveNotifications()
    {
        $user = Auth::User();
        $aPreferences = array("password_reset", "contest_end", "daily_deal_end", "coupon_end", "unredeemed", "new_offers", "love_offers");
        foreach($aPreferences as $preference)
        {
            if(Input::has($preference))
            {
                $pref = $preference.'_notification';
                $user->$pref = 1;
            }
            else
            {
                $pref = $preference.'_notification';
                $user->$pref = 0;
            }
        }
        $user->save();
        return Redirect::to('members/mynotifications')->with(array('settings_saved' => 'true'));
    }

    public function postChangePassword()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'current_password' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            )
        );
        if ($validator->fails())
        {
            return Redirect::to('members/mysettings')->withErrors($validator);
        }
        if(!Auth::validate(array('email' => Auth::User()->email, 'password' => Input::get('current_password'))))
        {
            return Redirect::to('members/mysettings')->with(array('invalid_password' => 'true'));
        }
        $user = Auth::User();
        $user->password = Input::get('password');
        $user->save();
        return Redirect::to('members/mysettings')->with(array('password_changed' => 'true'));
    }

    public function postChangeUserInfo()
    {
        $user = Auth::User();
        if($user->email != Input::get('email'))
        {
            $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'required|email|unique:users'
            ));
            if($validator->fails())
            {
                return Redirect::to('members/mysettings')->withErrors($validator);
            }
            $this->appEmail->changeEmail($user->email, Input::get('email'));
        }

        $user->email = Input::get('email');
        $user->name = Input::get('name');
        $user->birthday = Input::get('year').'-'.Input::get('month').'-'.Input::get('day').' 00:00:00';
        $sex = Input::get('gender');
        $user->sex = $sex == 'NoResponse' ? '' : $sex;
        $user->save();
        return Redirect::to('members/mysettings')->with(array('settings_saved' => 'true'));
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
            return Redirect::to('members/mysettings')->withErrors($validator);
        }
        /*if(!Auth::validate(array('email' => Auth::User()->email, 'password' => Input::get('current_password'))))
        {
            return Redirect::to('members/mysettings')->with(array('invalid_password' => 'true'));
        }*/
        $user = Auth::User();
        $user->password = Input::get('password');
        $user->save();
        return Redirect::to('members/dashboard')->with(array('password_changed' => 'true'));
    }

    public function getShareFacebook()
    {
        $eid = Input::get('eid');
        $entity = Entity::find($eid);
        $entity->facebookShare(Auth::User());
    }

    public function postDeleteAccount()
    {
        $user = Auth::User();
        $user->is_deleted = 1;
        $user->save();

        Auth::logout();

        return Redirect::to('/?modal=deleteAccountConfirmationModal');
    }

    public function getCruises()
    {
        $code = array();
        $vw = View::make('home.mycruises')->with('code', implode(' ', $code));
        $vw->title = "My Cruises";
        $vw->description = "Your Cruises.";
        $vw->page = 'cars';

        return $vw;
    }

}
<?php

class SoctController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| SoCT Controller ** DEPRECATED!!! See Cars Controller **
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'SoctController@showWelcome');
	|
	*/

    protected $geoip;
    protected $categoryRepository;
    protected $userRepository;
    protected $zipcodeRepository;
    protected $vehicleAssetRepository;
    protected $vehicleMakeRepository;
    protected $vehicleModelRepository;
    protected $vehicleStyleRepository;
    protected $vehicleYearRepository;
    protected $dealerApplicationRepository;

    /**
    *
    * Create a new controller instance.
    *
    * @param UserRepository $users
    *
    * @return void
    */
    public function __construct(
        AutoQuoteRepositoryInterface $autoQuoteRepository,
        CategoryRepositoryInterface $categoryRepository,
        DealerApplicationRepositoryInterface $dealerApplicationRepository,
        FeatureRepositoryInterface $features,
        UsedVehicleRepositoryInterface $usedVehicleRepository,
        UserRepositoryInterface $userRepository,
        VehicleAssetRepositoryInterface $vehicleAssetRepository,
        VehicleMakeRepositoryInterface $vehicleMakeRepository,
        VehicleModelRepositoryInterface $vehicleModelRepository,
        VehicleStyleRepositoryInterface $vehicleStyleRepository,
        VehicleYearRepositoryInterface $vehicleYearRepository,
        ZipcodeRepositoryInterface $zipcodeRepository)
    {
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->autoQuoteRepository = $autoQuoteRepository;
        $this->categoryRepository = $categoryRepository;
        $this->features = $features;
        $this->usedVehicleRepository = $usedVehicleRepository;
        $this->userRepository = $userRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->vehicleAssetRepository = $vehicleAssetRepository;
        $this->vehicleMakeRepository = $vehicleMakeRepository;
        $this->vehicleModelRepository = $vehicleModelRepository;
        $this->vehicleStyleRepository = $vehicleStyleRepository;
        $this->vehicleYearRepository = $vehicleYearRepository;
        $this->dealerApplicationRepository = $dealerApplicationRepository;
    }

/**
*This function retrieves the index page.
*/
	public function getIndex()
	{
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.index');
        $code[] = View::make('soct.jscode.search');
        //$code[] = View::make('home.jscode.tour');
        $vw = View::make('soct.index')->with('code', implode(' ', $code));
        $vw->title = "SaveOn - Download and Print Free Local and Retail Coupons";
        $vw->description = "SaveOn is your destination for online coupons and deals.";

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
        $new_car_end_year = $this->features->findByName('new_car_end_year');
        $new_car_end_year = $new_car_end_year ? $new_car_end_year->value : date('Y');
        $new_car_start_year = $this->features->findByName('new_car_start_year');
        $new_car_start_year = $new_car_start_year ? $new_car_start_year->value : date('Y');
        $vw->new_year_start = $new_car_start_year;
        $vw->new_year_end = $new_car_end_year;
        $vw->vehicle_years = SOE\DB\VehicleYear::where('year', '<=', $new_car_end_year)->groupBy('year')->orderBy('year','desc')->get();
        $vw->vehicle_makes = SOE\DB\VehicleMake::get();
        return $vw;
	}

    public function getUsed($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.used-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.used-cars')->with('code', implode(' ', $code));
        $vw->title = "Save On Used Cars";
        $vw->description = "Save On Used Cars";
        $vw->search_year = "all";
        $vw->search_make = "all";
        $vw->search_model = "all";
        
        $vw = $this->setSearchFilters($one, $two, $three, $four, $vw);

        return $vw;
    }

    public function getNew($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.new-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.new-cars')->with('code', implode(' ', $code));
        $vw->title = "Save On New Cars";
        $vw->description = "Save On New Cars";
        $vw->search_body = "all";
        $vw->search_year = "all";
        $vw->search_make = "all";
        $vw->search_model = "all";
        
        $vw = $this->setSearchFilters($one, $two, $three, $four, $vw);

        return $vw;
    }

    protected function setSearchFilters($one, $two, $three, $four, $vw)
    {
        $vw->search_year = $one ? $one : 'all';
        if($two)
        {
            $vw->search_make = $two;
            $vw->search_models = $this->vehicleModelRepository->getByMake($two);
        }
        $vw->search_model = $three ? $three : 'all';
        $vw->search_body = $four ? $four : 'all';
        $vw->search_min = Input::old('filterPriceMin', 'low');
        $vw->search_max = Input::old('filterPriceMax', 'high');
        $vw->search_distance = Input::old('filterDistance', 'high');

        return $vw;
    }

    public function getResearch($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        if($one == '')
        {
            return Redirect::to('/cars');
        }
        if (!is_numeric($one) || (strlen($one) != 4))
        {
            $make = $this->vehicleMakeRepository->getByName($one);
            if(empty($make))
            {
                $make = $this->vehicleMakeRepository->find($one);
            }
            if($two != '')
            {
                $model = $this->vehicleModelRepository->getByName($two);
                if(empty($model))
                {
                    $model = $this->vehicleModelRepository->find($two);
                }
                if (!empty($model))
                {
                    $code[] = View::make('soct.jscode.model');
                    $code[] = View::make('soct.jscode.search');
                    $vw = View::make('soct.model')->with('code', implode(' ', $code));
                    $vw->title = "Save On $make->name $model->name Vehicles";
                    $vw->description = "Save On $make->name $model->name Vehicles";
                    $vw->model = $model;
                    $model_years = $this->vehicleYearRepository->getByModel($model->id);
                    $vw->model_years = $model_years;
                    //print_r($model_years);
                    $vw->featuredCar = $model_years[0];

                    //print_r($model_years[0]);
                    //exit;

                    return $vw;
                }
            }
            if (!empty($make))
            {
                $code = array();
                $code[] = View::make('soct.jscode.make');
                $code[] = View::make('home.jscode.masonry');
                $code[] = View::make('soct.jscode.search');
                $vw = View::make('soct.make')->with('code', implode(' ', $code));
                $vw->title = "Save On $make->name Vehicles";
                $vw->description = "Save On $make->name Vehicles";
                $vw->make = $make;
                $vw->featuredCar = $this->vehicleStyleRepository->getRandom($make->id);
                return $vw;
            } else {
                return Redirect::to('/cars');
            }
            
        } else {
            $year = $this->vehicleYearRepository->getByYearMakeModel($one,$two,$three);
            $style = $this->vehicleStyleRepository->find($four);
            if (empty($style))
            {
                $style = $this->vehicleStyleRepository->find($two);
            }
            if (!empty($style))
            {
                $favorited = 0;
                if(Auth::check())
                {
                    $favorites = $this->userRepository->getFavorites(Auth::User()->id, 'SOE\\DB\\VehicleStyle', $style->id);
                    $favorited = $favorites['stats']['returned'];
                }
                $code = array();
                $code[] = View::make('soct.jscode.trim');
                $code[] = View::make('soct.jscode.search');
                $vw = View::make('soct.trim')->with('code', implode(' ', $code));
                $vw->title = "Save On the $style->year $style->make_name $style->model_name $style->name";
                $vw->description = "Save On the $style->year $style->make_name $style->model_name $style->name";
                $vw->year = $style->year;
                $vw->style = $style;
                $vw->favorited = $favorited;
                $vw->assets = $this->vehicleAssetRepository->getByStyle($style->id);
                $vw->model = $this->vehicleModelRepository->find($style->model_id);
                $reviews = $this->vehicleStyleRepository->getReviews($style->id);
                $is_reviewed = 0;
                $is_favorited = 0;
                if(Auth::check())
                {
                    $reviewFilters = array(array('type'=>'where','key'=>'reviewable_id','operator'=>'=','value'=>$style->id),
                        array('type'=>'where','key'=>'reviewable_type','operator'=>'=','value'=>'Location'),
                        array('key' => 'user_id', 'operator' => '=', 'value' => Auth::User()->id),
                        array('key' => 'is_deleted', 'operator' => '=', 'value' => '0'));
                    $my_review = Review::get($reviewFilters);
                    $is_reviewed = $my_review['stats']['returned'] == 0 ? 0: 1 ;
                    $fav = UserFavorite::get(array(array('key' => 'user_id', 'operator' => '=', 'value' => Auth::User()->id),
                                                array('key' => 'favoritable_type', 'operator' => '=', 'value' => 'Location'), 
                                                array('key' => 'favoritable_id', 'operator' => '=', 'value' => $style->id), 
                                                array('key' => 'is_deleted', 'operator' => '=', 'value' => '0') ));
                    $is_favorited = $fav['stats']['returned'] == 0 ? 0 : 1;
                }
                $vw->is_reviewed = $is_reviewed;
                $vw->is_favorited = $is_favorited;
                $vw->reviews = $reviews;
                $vw->user = Auth::check() ? Auth::User() : Auth::Nonmember();

                $new_year_start = $this->features->findByName('new_car_start_year', false);
                $new_year_end = $this->features->findByName('new_car_end_year', false);
                $vw->new_year_start = $new_year_start ? $new_year_start->value : date('Y');
                $vw->new_year_end = $new_year_end ? $new_year_end->value : date('Y');

                return $vw;
            } else {
                return Redirect::to('/cars');
            }
        }
    }

    public function getNewCars()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.new-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.new-cars')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }
    public function getUsedCars()
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.used-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.used-cars')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }
    public function getAutoServices()
    {
        $code = array();
        $code[] = View::make('soct.jscode.auto-services');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.auto-services')->with('code', implode(' ', $code));
        $subcategory = $this->categoryRepository->findBySlug('auto-dealers');
        $vw->subcategory = $subcategory;
        $vw->title = "Save On Service & Lease Specials";
        $vw->description = "Save On Service & Lease Specials";

        return $vw;
    }

    public function getFeaturedDealers()
    {
        $code = array();
        $code[] = View::make('soct.jscode.featured-dealers');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.featured-dealers')->with('code', implode(' ', $code));
        //$subcategory = $this->categoryRepository->findBySlug('auto-dealers');
        //$vw->subcategory = $subcategory;
        $vw->title = "Save On Featured Dealers";
        $vw->description = "Save On Featured Dealers";

        return $vw;
    }

    public function getOops()
    {
        $code = array();
        $vw = View::make('soct.oops')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postVehicleSearch()
    {
        //print_r(Input::all());exit;
        if(Input::get('carType') == 'used')
            $url = '/cars/used/';
        else
            $url = '/cars/new/';
        if(Input::get('filterYear') != '')
            $url .= Input::get('filterYear').'/';
        if(Input::get('filterMake') != '')
            $url .= Input::get('filterMake').'/';
        if(Input::get('filterModel') != '')
            $url .= Input::get('filterModel').'/';
        if(Input::get('filterBodyType') != '' && Input::get('carType') == 'new')
            $url .= Input::get('filterBodyType').'/';
        rtrim($url, '/');
        return Redirect::to($url)->withInput();
    }

    public function getDealerSignup()
    {
        $code = array();
        $vw = View::make('soct.dealer-signup')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postDealerSignup()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'business_name' => 'required',
                'primary_contact' => 'required',
                'contact_email' => 'required|email',
                'contact_phone' => 'required',
                'address1' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'country' => 'required',
                'account_password' => 'required|confirmed',
            )
        );
        if($validator->fails())
        {
            return Redirect::to('/cars/dealer-signup')->withInput()->withErrors($validator);
        }
        $application = $this->dealerApplicationRepository->create($inputs);
        if(empty($application))
            return Redirect::to('/cars/dealer-signup')->withInput()->with(array('system_error' => true));

        return Redirect::to('/cars/dealer-signup2')->with(array('application_id' => $application->id));
    }

    public function getDealerSignup2()
    {
        $code = array();
        $vw = View::make('soct.dealer-signup2')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postDealerSignup2()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'lead_email' => 'required',
                'lead_amount' => 'required'
            )
        );
        if($validator->fails())
        {
            return Redirect::to('/cars/dealer-signup2')->withInput()->withErrors($validator);
        }

        $application = $this->dealerApplicationRepository->find(Input::get('application_id'));
        if(empty($application))
            return Redirect::to('/cars/dealer-signup')->withInput()->with(array('system_error' => true));

        $application->lead_email = Input::get('does_background_checks');
        $application->description = Input::get('description');
        $application->new_inventory_number = Input::get('new_inventory');
        $application->used_inventory_number = Input::get('used_inventory');
        $application->lead_amount = Input::get('lead_amount');
        $application->market = Input::get('market');
        $application->save();

        return Redirect::to('/cars/dealer-signup3');
    }

    public function getDealerSignup3()
    {
        $code = array();
        $vw = View::make('soct.dealer-signup3')->with('code', implode(' ', $code));
        $vw->title = "Save On";
        $vw->description = "Save On";

        return $vw;
    }

    public function postUsedAutoQuote()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'usedQuoteVehicle' => 'required',
                'usedQuoteFirst' => 'required',
                'usedQuoteLast' => 'required',
                'usedQuoteEmail' => 'required|email',
                'usedQuotePhone' => 'required',
                'usedQuoteZipcode' => 'required'
            )
        );
        $parsed = parse_url(Request::header('referer'));
        if($validator->fails())
        {
            return Redirect::to($parsed['path'].'?eidtype=usedquote&showeid='.Input::get('usedQuoteVehicle'))->withErrors($validator)->withInput();
        }
        $vehicle = $this->usedVehicleRepository->find(Input::get('usedQuoteVehicle'));

        $data = array(
            'quoteable_id' => Input::get('usedQuoteVehicle'),
            'quoteable_type' => 'SOE\DB\UsedVehicle',
            'franchise_id' => $vehicle->merchant->franchises()->first()->id,
            'first_name' => Input::get('usedQuoteFirst'),
            'last_name' => Input::get('usedQuoteLast'),
            'email' => Input::get('usedQuoteEmail'),
            'phone' => Input::get('usedQuotePhone'),
            'user_id' => Auth::check() ? Auth::User()->id : 0,
            'zip' => Input::get('usedQuoteZipcode')
        );
        $quote = $this->autoQuoteRepository->create($data);
        return Redirect::to($parsed['path'].'?modal=quoteThanksModal');
    }

    public function postNewAutoQuote()
    {
        $inputs = Input::all();
        $validator = Validator::make(
            $inputs,
            array(
                'newQuoteVehicle' => 'required',
                'newQuoteFirst' => 'required',
                'newQuoteLast' => 'required',
                'newQuoteEmail' => 'required|email',
                'newQuotePhone' => 'required',
                'newQuoteZipcode' => 'required'
            )
        );
        if($validator->fails())
        {
            return Redirect::to(Request::header('referer').'?eidtype=newquote&showeid='.Input::get('newQuoteVehicle'))->withErrors($validator)->withInput();
        }
        $vehicle = $this->vehicleStyleRepository->find(Input::get('newQuoteVehicle'));

        $data = array(
            'quoteable_id' => Input::get('newQuoteVehicle'),
            'quoteable_type' => 'SOE\DB\VehicleStyle',
            'franchise_id' => Input::get('newQuoteFranchise', 0),
            'first_name' => Input::get('newQuoteFirst'),
            'last_name' => Input::get('newQuoteLast'),
            'email' => Input::get('newQuoteEmail'),
            'phone' => Input::get('newQuotePhone'),
            'user_id' => Auth::User()->id,
            'zip' => Input::get('newQuoteZipcode')
        );
        $quote = $this->autoQuoteRepository->create($data);

        return Redirect::to(Request::header('referer'));
    }

}

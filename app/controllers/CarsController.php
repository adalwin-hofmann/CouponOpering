<?php

class CarsController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AdvertisementRepositoryInterface $advertisements,
        AutoQuoteRepositoryInterface $autoQuotes,
        AssetRepositoryInterface $assets,
        CategoryRepositoryInterface $categories,
        CityImageRepositoryInterface $cityImages,
        CompanyRepositoryInterface $companies,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $features,
        FranchiseRepositoryInterface $franchises,
        LocationRepositoryInterface $locations,
        MerchantRepositoryInterface $merchants,
        NonmemberRepositoryInterface $nonmembers,
        UserImpressionRepositoryInterface $userImpressions,
        UsedVehicleRepositoryInterface $usedVehicles,
        UserLocationRepositoryInterface $userLocations,
        UserRepositoryInterface $users,
        VehicleAssetRepositoryInterface $vehicleAssets,
        VehicleEntityRepositoryInterface $vehicleEntities,
        VehicleMakeRepositoryInterface $vehicleMakes,
        VehicleModelRepositoryInterface $vehicleModels,
        VehicleStyleRepositoryInterface $vehicleStyles,
        VehicleYearRepositoryInterface $vehicleYears,
        ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->advertisements = $advertisements;
        $this->autoQuotes = $autoQuotes;
        $this->assets = $assets;
        $this->categories = $categories;
        $this->cityImages = $cityImages;
        $this->companies = $companies;
        $this->entities = $entities;
        $this->features = $features;
        $this->franchises = $franchises;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->nonmembers = $nonmembers;
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->userImpressions = $userImpressions;
        $this->usedVehicles = $usedVehicles;
        $this->userLocations = $userLocations;
        $this->users = $users;
        $this->vehicleAssets = $vehicleAssets;
        $this->vehicleEntities = $vehicleEntities;
        $this->vehicleMakes = $vehicleMakes;
        $this->vehicleModels = $vehicleModels;
        $this->vehicleStyles = $vehicleStyles;
        $this->vehicleYears = $vehicleYears;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->zipcodes = $zipcodes;

        $premium = $this->advertisements->search('soct', 'premium', 'page');
        $this->premium = $premium;
        View::share('premium', $premium);

        parent::__construct();
    }

    /**
     * This is the base route for cars.
     */
    public function getIndex()
    {
        /*if(!$state)
            return Redirect::to('/cars/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name), 301);

        if(Input::has('chg'))
        {
            $url = '/cars/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            return Redirect::to($url.$eid);
        }

        $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
        $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));*/

        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.index');
        $code[] = View::make('soct.jscode.search');
        //$code[] = View::make('home.jscode.tour');
        $vw = View::make('soct.index')->with('code', implode(' ', $code));
        $vw->title = "Save On Used Cars for Sale, New Car Prices and Dealer Specials";
        $vw->description = "Save On Used Cars for Sale, New Car Prices and Dealer Specials.";

        $city_image = $this->cityImages->getNearbyCity($this->geoip->region_name, $this->geoip->latitude, $this->geoip->longitude);
        $vw->city_image = $city_image;
        $vw->geoip = $this->geoip;
        $new_car_end_year = $this->features->findByName('new_car_end_year');
        $new_car_end_year = $new_car_end_year ? $new_car_end_year->value : date('Y');
        $new_car_start_year = $this->features->findByName('new_car_start_year');
        $new_car_start_year = $new_car_start_year ? $new_car_start_year->value : date('Y');
        $vw->new_year_start = $new_car_start_year;
        $vw->new_year_end = $new_car_end_year;

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
            $recommendations = $this->users->getRecommendations($user, 12, $geoip, 'dist', 'soct');
        }
        else
        {
            $nonmember = Auth::nonmember();
            $recommendations = $this->nonmembers->getRecommendations($nonmember, 12, $geoip, 'dist', 'soct');
        }
        $vw->entities = $recommendations['objects'];

        $this->trackPremiumView();

        $featuredDealer = $this->franchises->getFeaturedDealer($geoip->latitude, $geoip->longitude, true);
        $vw->featuredDealer = $featuredDealer;
        $featuredVehicle = null;
        if(!count($featuredDealer['objects']))
        {
            $featuredVehicle = $this->vehicleEntities->getFeaturedVehicle($geoip->latitude, $geoip->longitude, $geoip->region_name);
        }
        $vw->featuredVehicle = $featuredVehicle;

        return $vw;
    }

    public function newCars($state = null, $city = null, $year = null, $make = null, $model = null, $dealer = null)
    {
        /*if(!$state)
            return Redirect::to('/cars/new/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name), 301);*/

        $lowerRedirect = $this->checkLowercase('new', $state, $city, $year, $make, $model, $dealer);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($state && !$city)
            return $this->newCities($state);

        if(Input::has('chg'))
        {
            $url = '/cars/new/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $make ? '/'.$make : '';
            $url .= $model ? '/'.$model : '';
            $url .= $dealer ? '/'.$dealer : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            return Redirect::to($url.$eid);
        }

        if($state && $city)
        {
            $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
            if(!$zipcode)
                return App::abort(404);
            $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
            $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        }

        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.new-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.new-cars')->with('code', implode(' ', $code));
        $vw->title = "Save On New Cars";
        $vw->description = "Save On New Cars";

        $vw->search_year = $year ? $year : 'all';
        $vw->search_make = $make ? $make : 'all';
        if($make)
            $vw->search_models = $this->vehicleModels->getByMake($make);
        $vw->search_model = $model ? $model : 'all';
        $vw->search_body = Input::old('filterBodyType', 'all');
        $vw->geoip = $this->geoip;
        $vw->search_min = Input::old('filterPriceMin', 'low');
        $vw->search_max = stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $vw->search_distance = Input::old('filterDistance', 'high');
        $vw->search_dealer = $dealer ? $dealer : 'all';
        $vw->state = $state;
        $vw->city = $city;
        $vw->page_type = 'new';

        $bodyType = Input::old('filterBodyType', null);
        $min = Input::old('filterPriceMin', null);
        $max = stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', null);
        $vehicles = $this->vehicleStyles->search(
            $year != 'all' ? $year : null,
            $make != 'all' ? $make : null,
            $model != 'all' ? $model : null,
            $min != 'low' ? $min : null,
            $max != 'high' ? $max : null,
            Input::get('page', 0),
            12,
            'popularity',
            $bodyType != 'all' ? $bodyType : null
        );
        foreach($vehicles['objects'] as &$vehicle)
        {
            if(count($vehicle->display_image))
                $vehicle->display_image = $vehicle->display_image[0]->path;
            else
            {
                // TODO: Add placeholder car image.
                $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                $vehicle->display_image = $image;
            }
        }
        $vw->vehicles = $vehicles['objects'];

        $this->trackPremiumView();

        return $vw;
    }

    public function oldRedirects($state, $city, $year, $make = null, $model = null, $dealer = null)
    {
        if(!$make)
        {
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            $year = $year != 'all' ? ($eid != '' ? '&year='.$year : '?year='.$year) : '';
            return Redirect::to('/cars/used/'.strtolower($state).'/'.SoeHelper::getSlug($city).$eid.$year, 301);
        }

        if(!$model)
        {
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            $year = $year != 'all' ? ($eid != '' ? '&year='.$year : '?year='.$year) : '';
            return Redirect::to('/cars/used/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/'.$make.$eid.$year, 301);
        }

        if(!$dealer)
        {
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            $year = $year != 'all' ? ($eid != '' ? '&year='.$year : '?year='.$year) : '';
            $model = $model != 'all' ? ($eid != '' || $year != '' ? '&model='.$model : '?model='.$model) : '';
            return Redirect::to('/cars/used/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/'.$make.$eid.$year.$model, 301);
        }

        $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
        $year = $year != 'all' ? ($eid != '' ? '&year='.$year : '?year='.$year) : '';
        $model = $model != 'all' ? ($eid != '' || $year != '' ? '&model='.$model : '?model='.$model) : '';
        $max = stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $max = $max == 'high' ? '' : '/'.$max;

        return Redirect::to('/cars/used/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/'.$make.$max.$eid.$year.$model, 301);
    }

    public function usedCars($state = null, $city = null, $make = null, $price = null, $style = null)
    {
        if(!$state)
            return $this->usedLanding();
        if(!$city)
            return $this->usedCities($state);

        return $this->usedSearch($state, $city, $make, $price, 'used', $style);
    }

    public function usedUnder($price = null, $make = null, $state = null, $city = null, $style = null)
    {
        if(!$price)
            return $this->underLanding();
        if(!$make)
            return $this->underMakes($price);
        if(!$state)
            return $this->underStates($price, $make);

        return $this->underSearch($state, $city, $make, $price, $style);
    }

    protected function usedSearch($state = null, $city = null, $make = null, $price = null, $type = 'used', $style = null)
    {

        if(Input::has('chg'))
        {
            if($type == 'used')
            {
                $url = '/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
                $url .= $make ? '/'.$make : '';
                $url .= $price ? '/'.$price : '';
            }
            else
            {
                $url = '/cars/used/under/'.$price.'/'.$make.'/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            }
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid').'&eidtype=usedvehicle&vendor='.Input::get('vendor') : '';
            return Redirect::to($url.$eid);
        }

        if($state && $city)
        {
            $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
            if(!$zipcode)
                return App::abort(404);
            $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
            $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        }

        $states = SoeHelper::states();
        $fullState = $states['USA']['states'][strtoupper($state)];

        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.used-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.used-cars')->with('code', implode(' ', $code));
        if ((!$make) || $make == 'all')
        {
            $vw->title = "Used Cars in ".ucwords(SoeHelper::unSlug($city))." ".strtoupper($state)." For Sale - Save On ".ucwords(SoeHelper::unSlug($city))." Used Trucks, Vans and Vehicles";
            $vw->description = "Search used cars for sale in ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." at exclusive ".ucwords(SoeHelper::unSlug($city))." auto dealerships. See ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." used cars and trucks with huge savings. SaveOn ".ucwords(SoeHelper::unSlug($city))." ".ucwords($fullState)." featured pre owned vehicles.";
        } 
        elseif (!$price) 
        {
            $vw->title = "Used ".ucwords(SoeHelper::unSlug($make))." For Sale ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." - Used Deals in ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." | SaveOn";
            $vw->description = ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." used ".ucwords(SoeHelper::unSlug($make))." deals at SaveOn ".ucwords(SoeHelper::unSlug($city)).". Find a pre-owned ".ucwords(SoeHelper::unSlug($make))." for sale in ".ucwords(SoeHelper::unSlug($city))." ".strtoupper($state)." and save big with exclusive ".ucwords(SoeHelper::unSlug($city))." used car dealers. SaveOn Cars and Trucks across ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state).".";
        } else {
            $vw->title = "Used ".ucwords(SoeHelper::unSlug($make))." For Sale ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." - ".ucwords(SoeHelper::unSlug($make))." Under $".number_format($price)." Near Me | SaveOn";
            $vw->description = ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." used ".ucwords(SoeHelper::unSlug($make))." deals at SaveOn ".ucwords(SoeHelper::unSlug($city)).". Find a pre-owned ".ucwords(SoeHelper::unSlug($make))." for sale in ".ucwords(SoeHelper::unSlug($city))." ".strtoupper($state)." and save big with exclusive ".ucwords(SoeHelper::unSlug($city))." used car dealers. SaveOn Cars and Trucks across ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state).".";
        }
        
        $year = Input::get('year', 'all');
        $vw->search_year = $year;
        $vw->search_make = $make ? $make : 'all';
        if($make)
            $vw->search_models = $this->vehicleModels->getByMake($make);
            $vw->make = $make;
        if($price)
            $vw->price = $price;
        $model = Input::get('model', 'all');;
        $vw->search_model = $model;
        $vw->search_body = Input::old('filterBodyType', isset($style)?$style:'all');
        $vw->geoip = $this->geoip;
        $vw->search_min = Input::old('filterPriceMin', 'low');
        $vw->search_max = ($price)?$price:Input::old('filterPriceMax', 'high');//stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $vw->search_distance = Input::old('filterDistance', 'high');
        $vw->search_dealer = 'all';//$dealer ? $dealer : 'all';
        $vw->state = $state;
        $vw->city = $city;
        $vw->page_type = 'used';
        $vw->style = $style;
        
        $min = Input::old('filterPriceMin', 'low');
        $max = ($price)?$price:Input::old('filterPriceMax', 'high');//stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $dist = Input::old('filterDistance', 'high');
        $mileage = Input::get('mileage', null);

        if ($style == 'truck')
        {
            $style = 'cab,truck,pickup';
        }

        $vehicles = $this->vehicleEntities->search(
            $year != 'all' ? $year : null, 
            $make != 'all' ? $make : null, 
            $model != 'all' ? $model : null, 
            $min != 'low' ? $min : null, 
            $max != 'high' ? $max : null, 
            $dist != 'high' ? $dist : null,
            Input::get('page', 0),
            12,
            'rand',
            $mileage,
            $style,
            $state,
            true
        );

        $params = parse_url(URL::full(), PHP_URL_QUERY);
        $vw->params = $params;

        $vehicles = $this->getDisplayImage($vehicles);
        $vw->vehicles = $vehicles['objects'];
        $featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);
        $vw->featuredDealer = $featuredDealer;
        $featuredVehicle = null;
        if(!count($featuredDealer['objects']))
        {
            $featuredVehicle = $this->vehicleEntities->getFeaturedVehicle($this->geoip->latitude, $this->geoip->longitude, $this->geoip->region_name);
        }
        $vw->featuredVehicle = $featuredVehicle;

        $this->trackPremiumView();

        return $vw;
    }

    protected function usedLanding()
    {
        $code = array();
        $code[] = View::make('soct.jscode.used-landing');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.used-landing')->with('code', implode(' ', $code));
        $lows = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            15000, 
            null,
            0,
            3,
            'dist',
            null,
            null,
            null,
            false
        );
        if (count($lows['objects']) == 0)
        {
            $lows = $this->vehicleEntities->search(
                null, 
                null, 
                null, 
                null, 
                15000, 
                null,
                0,
                3,
                'dist',
                null,
                null,
                null,
                false,
                false
            );
        }
        $lows = $this->getDisplayImage($lows);
        $vw->lows = $lows['objects'];

        $lowMiles = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'dist',
            50000,
            null,
            null,
            false
        );
        if (count($lowMiles['objects']) == 0)
        {
            $lowMiles = $this->vehicleEntities->search(
                null, 
                null, 
                null, 
                null, 
                null, 
                null,
                0,
                4,
                'dist',
                50000,
                null,
                null,
                false,
                false
            );
        }
        $lowMiles = $this->getDisplayImage($lowMiles);
        $vw->lowMiles = $lowMiles['objects'];
        
        $convertibles = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'dist',
            null,
            'convertible',
            null,
            false
        );
        if (count($convertibles['objects']) == 0)
        {
            $convertibles = $this->vehicleEntities->search(
                null, 
                null, 
                null, 
                null, 
                null, 
                null,
                0,
                4,
                'dist',
                null,
                'convertible',
                null,
                false,
                false
            );
        }
        $convertibles = $this->getDisplayImage($convertibles);
        $vw->convertibles = $convertibles['objects'];

        $trucks = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'dist',
            null,
            'cab,truck,pickup',
            null,
            false
        );
        if (count($trucks['objects']) == 0)
        {
            $trucks = $this->vehicleEntities->search(
                null, 
                null, 
                null, 
                null, 
                null, 
                null,
                0,
                4,
                'dist',
                null,
                'cab,truck,pickup',
                null,
                false,
                false
            );
        }
        $trucks = $this->getDisplayImage($trucks);
        $vw->trucks = $trucks['objects'];

        $states = SoeHelper::states();
        $vw->states = $states['USA']['states'];

        $vw->title = "Cars For Sale - Shop, Save and Buy Cars Online - Used Vehicles | SaveOn";;
        $vw->description = "Shop used cars online and save! Find the best car for sale at local auto dealers in your area. Exclusive local used car deals online. SaveOn Cars and Trucks nationwide.";
        $vw->page_type = 'used';
        $vw->geoip = $this->geoip;

        $this->trackPremiumView();

        return $vw;
    }

    public function getUsedLanding()
    {
        $code = array();
        //$code[] = View::make('home.jscode.landing');
        $vw = View::make('soct.used-landing')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Used Cars";
        $vw->description = "SaveOn Used Cars";

        return $vw;
    }

    protected function usedCities($state)
    {
        $type = 'used';
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        //$code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $found = false;
        $stateName = '';
        // Check if this is a state
        $found = $this->checkState($state);
        $stateName = $found;

        $states = SoeHelper::states();
        $fullState = $states['USA']['states'][strtoupper($state)];

        // This is a state, redirect to cities page.
        $vw = View::make('soct.used-cities')->with('code', implode(' ', $code));
        $title = "Used Cars For Sale In ".ucwords(strtolower($fullState))." - Best Car and Truck Deals in ".strtoupper($state)." | SaveOn";
        $description = "Save money on used cars in ".ucwords(strtolower($fullState))." for sale with SaveOn ".ucwords(strtolower($fullState))." auto dealers. Locate the best car and truck deals in ".strtoupper($state)." at SaveOn.com. Exclusive ".ucwords(strtolower($fullState))." used car deals and savings events.";
        
        $lows = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            15000, 
            null,
            0,
            3,
            'rand',
            null,
            null,
            $state,
            false
        );
        $lows = $this->getDisplayImage($lows);
        $vw->lows = $lows['objects'];

        $lowMiles = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'rand',
            50000,
            null,
            $state,
            false
        );
        $lowMiles = $this->getDisplayImage($lowMiles);
        $vw->lowMiles = $lowMiles['objects'];
        
        $convertables = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'rand',
            null,
            'convertible',
            $state,
            false
        );
        $convertables = $this->getDisplayImage($convertables);
        $vw->convertables = $convertables['objects'];

        $trucks = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            4,
            'rand',
            null,
            'cab,truck,pickup',
            $state,
            false
        );
        $trucks = $this->getDisplayImage($trucks);
        $vw->trucks = $trucks['objects'];

        $city_image = $this->cityImages->findByState($state);
        $vw->subheader = ($city_image && $city_image->used_cars_sub_heading) ? SoeHelper::cityStateReplace($city_image->used_cars_sub_heading, $geoip) : '';
        $vw->title = $title;
        $vw->description = $description;
        $vw->active = 0;
        $vw->stateName = $stateName;
        $vw->state = $state;
        $cities = $this->locations->getTopCitiesByState($state);
        $vw->cities = $cities;
        $stateLocation = $cities['top_city'];
        $vw->top_city = empty($stateLocation) ?  '' : $stateLocation->city; //\Input::get('city');
        $vw->top_state = empty($stateLocation) ?  '' : $stateLocation->state; //\Input::get('state');
        $vw->top_latitude = empty($stateLocation) ?  '' : $stateLocation->latitude; //\Input::get('latitude');
        $vw->top_longitude = empty($stateLocation) ?  '' : $stateLocation->longitude; //\Input::get('longitude');
        $vw->city_image = $city_image;
        $vw->page_type = $type;
        $vw->geoip = $this->geoip;

        $states = SoeHelper::states();
        $vw->states = $states['USA']['states'];

        $this->trackPremiumView();

        return $vw;
    }

    protected function newCities($state)
    {
        $type = 'new';
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        //$code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $found = false;
        $stateName = '';
        // Check if this is a state
        $found = $this->checkState($state);
        $stateName = $found;

        $states = SoeHelper::states();
        $fullState = $states['USA']['states'][strtoupper($state)];

        // This is a state, redirect to cities page.
        $vw = View::make('soct.new-cities')->with('code', implode(' ', $code));
        $title = "Save On ".ucwords($type)." Cars in ".strtoupper($state);
        $description = "Save On ".ucwords($type)." Cars in ".strtoupper($state);

        $lows = $this->vehicleStyles->search(
            null,
            null,
            null,
            null,
            15000,
            0,
            3,
            'rand',
            null
        );
        foreach($lows['objects'] as &$vehicle)
        {
            if(count($vehicle->display_image))
                $vehicle->display_image = $vehicle->display_image[0]->path;
            else
            {
                // TODO: Add placeholder car image.
                $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                $vehicle->display_image = $image;
            }
        }
        $vw->lows = $lows['objects'];

        $luxury = $this->vehicleStyles->search(
            null,
            null,
            null,
            70000,
            null,
            0,
            3,
            'rand',
            null
        );
        foreach($luxury['objects'] as &$vehicle)
        {
            if(count($vehicle->display_image))
                $vehicle->display_image = $vehicle->display_image[0]->path;
            else
            {
                // TODO: Add placeholder car image.
                $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                $vehicle->display_image = $image;
            }
        }
        $vw->luxury = $luxury['objects'];

        $suv = $this->vehicleStyles->search(
            null,
            null,
            null,
            null,
            null,
            0,
            3,
            'rand',
            'suv'
        );
        foreach($suv['objects'] as &$vehicle)
        {
            if(count($vehicle->display_image))
                $vehicle->display_image = $vehicle->display_image[0]->path;
            else
            {
                // TODO: Add placeholder car image.
                $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                $vehicle->display_image = $image;
            }
        }
        $vw->suv = $suv['objects'];

        $trucks = $this->vehicleStyles->search(
            null,
            null,
            null,
            null,
            null,
            0,
            3,
            'rand',
            'truck'
        );
        foreach($trucks['objects'] as &$vehicle)
        {
            if(count($vehicle->display_image))
                $vehicle->display_image = $vehicle->display_image[0]->path;
            else
            {
                // TODO: Add placeholder car image.
                $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                $vehicle->display_image = $image;
            }
        }
        $vw->trucks = $trucks['objects'];

        $city_image = $this->cityImages->findByState($state);
        $vw->title = $title;
        $vw->description = $description;
        $vw->subheader = ($city_image->new_cars_sub_heading) ? SoeHelper::cityStateReplace($city_image->new_car_sub_heading, $geoip) : '';
        $vw->active = 0;
        $vw->stateName = $stateName;
        $vw->state = $state;
        $cities = $this->locations->getTopCitiesByState($state);
        $vw->cities = $cities;
        $stateLocation = $cities['top_city'];
        $vw->top_city = $stateLocation->city; //\Input::get('city');
        $vw->top_state = $stateLocation->state; //\Input::get('state');
        $vw->top_latitude = $stateLocation->latitude; //\Input::get('latitude');
        $vw->top_longitude = $stateLocation->longitude; //\Input::get('longitude');
        $vw->city_image = $city_image;
        $vw->page_type = $type;
        $vw->geoip = $this->geoip;

        $states = SoeHelper::states();
        $vw->states = $states['USA']['states'];
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        return $vw;
    }

    protected function cities($state, $type)
    {
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $found = false;
        $stateName = '';
        // Check if this is a state
        $found = $this->checkState($state);
        $stateName = $found;

        $states = SoeHelper::states();
        $fullState = $states['USA']['states'][strtoupper($state)];

        // This is a state, redirect to cities page.
        $vw = View::make('soct.cities')->with('code', implode(' ', $code));
        $title = '';
        $description;
        switch ($type) {
            case 'used':
                $title = "Used Cars For Sale In ".ucwords(strtolower($fullState))." - Best Car and Truck Deals in ".strtoupper($state)." | SaveOn";
                $description = "Save money on used cars in ".ucwords(strtolower($fullState))." for sale with SaveOn ".ucwords(strtolower($fullState))." auto dealers. Locate the best car and truck deals in ".strtoupper($state)." at SaveOn.com. Exclusive ".ucwords(strtolower($fullState))." used car deals and savings events.";
                $vehicles = $this->vehicleEntities->search(
                    null, 
                    null, 
                    null, 
                    null, 
                    null, 
                    null,
                    0,
                    12,
                    'rand',
                    null,
                    null,
                    $state,
                    false
                );
                $vehicles = $this->getDisplayImage($vehicles);
                $vw->vehicles = $vehicles['objects'];
                //print_r($vehicles['objects']);
                //exit;
                $vw->moreLink = URL::abs('/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name));
                break;
            case 'new':
                $title = "Save On ".ucwords($type)." Cars in ".strtoupper($state);
                $description = "Save On ".ucwords($type)." Cars in ".strtoupper($state);
                $vehicles = $this->vehicleStyles->search(
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                    12,
                    'rand',
                    null
                );
                foreach($vehicles['objects'] as &$vehicle)
                {
                    if(count($vehicle->display_image))
                        $vehicle->display_image = $vehicle->display_image[0]->path;
                    else
                    {
                        // TODO: Add placeholder car image.
                        $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                        $vehicle->display_image = $image;
                    }
                }
                $vw->vehicles = $vehicles['objects'];
                $vw->moreLink = URL::abs('/cars/new/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name));
                break;
            case 'auto-services':
                $title = "Service & Lease Specials in ".strtoupper($state);
                $description = "Service & Lease Specials in ".strtoupper($state);
                break;
            case 'featured-dealers':
                $title = "Featured Dealers in ".strtoupper($state);
                $description = "Featured Dealers in ".strtoupper($state);
                break;
        }
        $vw->title = $title;
        $vw->description = $description;
        $vw->active = 0;
        $vw->stateName = $stateName;
        $vw->state = $state;
        $vw->cities = $this->locations->getTopCitiesByState($state);
        $city_image = $this->cityImages->findByState($state);
        $vw->city_image = $city_image;
        $vw->page_type = $type;
        $vw->geoip = $this->geoip;
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($geoip->latitude, $geoip->longitude, true);

        return $vw;
    }

    protected function underLanding()
    {
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $type = 'used';

        // This is a state, redirect to cities page.
        $vw = View::make('soct.under-landing')->with('code', implode(' ', $code));
        
        $title = "Used Cars For Sale - Auto Deals Near You | SaveOn Cars and Trucks ";
        $description = "Find cars under $5,000 , $10,000, $15,000  or search for cheap used cars below $3,000, $2,500 and even $1,000! Save on pre-owned vehicles for sale right in your area.";

        $vehicles = $this->vehicleEntities->search(
            null,
            null,
            null,
            null,
            null,
            null,
            0,
            12,
            'dist'
        );
        $vehicles = $this->getDisplayImage($vehicles);
        $vw->vehicles = $vehicles['objects'];
        $vw->moreLink = URL::abs('/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name));

        $vw->title = $title;
        $vw->description = $description;
        $vw->active = 0;

        $vw->page_type = $type;
        $vw->geoip = $this->geoip;
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        return $vw;
    }

    protected function underMakes($price)
    {
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $type = 'used';

        // This is a state, redirect to cities page.
        $vw = View::make('soct.under-makes')->with('code', implode(' ', $code));
        
        $title = "Best Used Cars Under $".number_format($price)." - Save On Vehicles Under $".number_format($price)." | SaveOn";
        $description = "Vehicles below $".number_format($price)." for sale in your area. Check out the best used cars under $".number_format($price)." near you. See exclusive deals from local dealerships. SaveOn cars less than $".number_format($price)." nationwide.";

        $vehicles = $this->vehicleEntities->search(
            null,
            null,
            null,
            null,
            $price,
            null,
            0,
            12,
            'dist'
        );


        $vehicles = $this->getDisplayImage($vehicles);
        $vw->vehicles = $vehicles['objects'];
        $vw->moreLink = URL::abs('/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name));

        $makes = $this->vehicleMakes->getActiveMakes();
        $vw->makes = $makes;
        $vw->price = $price;

        $vw->title = $title;
        $vw->description = $description;
        $vw->active = 0;

        $vw->page_type = $type;
        $vw->geoip = $this->geoip;
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        return $vw;
    }

    protected function underStates($price, $make)
    {
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.cities');
        $type = 'used';

        $make = $this->vehicleMakes->getByName($make);

        $vw = View::make('soct.under-makes')->with('code', implode(' ', $code));
        
        $title = "Used ".$make->name." Vehicles Under $".number_format($price)." - ".$make->name." Cars For Sale | Save On";
        $description = "Save on used ".$make->name." cars and trucks under $".number_format($price).". Find the best used [make] below $".number_format($price)." for sale near you. Search all pre-owned ".$make->name." cars, trucks, SUV's and vans nationwide.";

        $vehicles = $this->vehicleEntities->search(
            null, 
            $make->slug,
            null, 
            null, 
            $price,
            null,
            0,
            12,
            'rand'
        );
        $vehicles = $this->getDisplayImage($vehicles);
        $vw->vehicles = $vehicles['objects'];
        $vw->moreLink = URL::abs('/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name));

        $makes = $this->vehicleMakes->getActiveMakes();
        $vw->makes = $makes;
        $vw->price = $price;
        $vw->make = $make;

        $vw->title = $title;
        $vw->description = $description;
        $vw->active = 0;

        $vw->page_type = $type;
        $vw->geoip = $this->geoip;
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        return $vw;
    }

    protected function underSearch($state = null, $city = null, $make = null, $price = null, $style = null)
    {

        $type = 'used';
        if(Input::has('chg'))
        {
            if($type == 'used')
            {
                $url = '/cars/used/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
                $url .= $make ? '/'.$make : '';
                $url .= $price ? '/'.$price : '';
            }
            else
            {
                $url = '/cars/used/under/'.$price.'/'.$make.'/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            }
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            return Redirect::to($url.$eid);
        }


        if($state && $city)
        {
            $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
            if(!$zipcode)
                return App::abort(404);
            $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
            $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        }

        $make = $this->vehicleMakes->getByName($make);
        if(empty($make))
            exit;

        $states = SoeHelper::states();
        $fullState = $states['USA']['states'][strtoupper($state)];
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.used-cars');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.under-cars')->with('code', implode(' ', $code));
        if($city != '')
        {
            $vw->title = ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." Used ".$make->name." Under $".number_format($price)." - ".ucwords(SoeHelper::unSlug($city))." Pre-Owned ".$make->name." Under $".number_format($price)." - | SaveOn";
            $vw->description = "Find the perfect used ".$make->name." under $15,000 in ".ucwords(SoeHelper::unSlug($city)).", ".strtoupper($state)." with SaveOn ".ucwords(SoeHelper::unSlug($city)).". See huge savings on pre-owned ".$make->name." models across ".ucwords(SoeHelper::unSlug($city))." ".ucwords(strtolower($fullState)).".";
        } else {
            $vw->title = "Used ".$make->name." in ".strtoupper($state)." Under $".number_format($price)." - ".ucwords(strtolower($fullState))." Used ".$make->name." For Sale | SaveOn";
            $vw->description = ucwords(strtolower($fullState))." used ".$make->name." cars under $15,000 for sale online. Save on used ".$make->name." vehicles in ".ucwords(strtolower($fullState))." below $15,000  from  SaveOn.com ".ucwords(strtolower($fullState))." auto dealers. Exclusive savings on used ".$make->name." cars, trucks, SUV's and vans $15,000 or less in ".strtoupper($state).".";
        }
        
        $year = Input::get('year', 'all');
        $vw->search_year = $year;
        $vw->search_make = $make ? $make->slug : 'all';
        if($make)
            $vw->search_models = $this->vehicleModels->getByMake($make->slug);
        $model = Input::get('model', 'all');
        $vw->search_model = $model;
        $vw->search_body = Input::old('filterBodyType', 'all');
        $vw->geoip = $this->geoip;
        $vw->search_min = Input::old('filterPriceMin', 'low');
        $vw->search_max = ($price)?$price:Input::old('filterPriceMax', 'high');//stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $vw->search_distance = Input::old('filterDistance', 'high');
        $vw->search_dealer = 'all';//$dealer ? $dealer : 'all';
        $vw->state = $state;
        $vw->city = $city;
        $vw->price = $price;
        $vw->make = $make;
        $vw->page_type = 'used';

        $vw->cities = $this->locations->getTopCitiesByState($state);
        
        $min = Input::old('filterPriceMin', 'low');
        $max = ($price)?$price:Input::old('filterPriceMax', 'high');//stristr($dealer, 'under-') ? substr($dealer, 6) : Input::old('filterPriceMax', 'high');
        $dist = Input::old('filterDistance', 'high');

        if ($style == 'truck')
        {
            $style = 'cab,truck,pickup';
        }
        $mileage = Input::get('mileage', null);

        $vehicles = $this->vehicleEntities->search(
            $year != 'all' ? $year : null, 
            $make->slug,
            $model != 'all' ? $model : null, 
            null, 
            $price,
            $dist != 'high' ? $dist : null,
            Input::get('page', 0),
            12,
            'dist',
            $mileage,
            $style,
            $state,
            true
        );

        $params = parse_url(URL::full(), PHP_URL_QUERY);
        $vw->params = $params;

        $vehicles = $this->getDisplayImage($vehicles);
        $vw->vehicles = $vehicles['objects'];
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        return $vw;
    }

    public function vehicleSearch()
    {
        if(Input::get('carType') == 'used')
        {
            $url = '/cars/used/';
        
            $url .= strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/';
            if(Input::get('filterMake') != '')
            {
                $url .= Input::get('filterMake').'/';
            } 
            elseif ((Input::get('filterPriceMax') != 'high') || (Input::get('filterBodyType') != '')) 
            {
                $url .= 'all/';
            }
            if(Input::get('filterPriceMax') != 'high')
            {
                $url .= Input::get('filterPriceMax').'/';
            }
            elseif (Input::get('filterBodyType') != '')
            {
                $url .= '100000/';
            }
            if(Input::get('filterBodyType') != '')
            {
                $url .= Input::get('filterBodyType');
            }

            $params = array();
            if(Input::get('filterYear') != '' && Input::get('filterYear') != 'all')
                $params['year'] = Input::get('filterYear');

            if(Input::get('filterModel') != '' && Input::get('filterModel') != 'all')
                $params['model'] = Input::get('filterModel');

            rtrim($url, '/');

            $url = count($params) ? $url.'?'.http_build_query($params) : $url;
        }
        else
        {
            $url = '/cars/new/';
            $url .= strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/';
            if(Input::get('filterYear') != '')
                $url .= Input::get('filterYear').'/';
            if(Input::get('filterMake') != '')
                $url .= Input::get('filterMake').'/';
            if(Input::get('filterModel') != '')
                $url .= Input::get('filterModel').'/';
            if(Input::get('filterDealer') != '' && Input::get('filterDealer') != 'all')
            {
                $url .= Input::get('filterDealer').'/';
            }
            else if(Input::get('filterPriceMax') != 'high')
            {
                $url .= 'under-'.Input::get('filterPriceMax').'/';
            }
        }

        return Redirect::to($url)->withInput();
    }

    public function research($one = '', $two = '', $three = '', $four = '')
    {
        if($one == '')
        {
            return Redirect::to('/cars');
        }
        if (!is_numeric($one) || (strlen($one) != 4))
        {
            $make = $this->vehicleMakes->getByName($one);
            if(empty($make))
            {
                $make = $this->vehicleMakes->find($one);
            }
            if($two != '')
            {
                $model = $this->vehicleModels->getByName($two);
                if(empty($model))
                {
                    $model = $this->vehicleModels->find($two);
                }
                if (!empty($model))
                {
                    $code[] = View::make('soct.jscode.model');
                    $code[] = View::make('soct.jscode.search');
                    $vw = View::make('soct.model')->with('code', implode(' ', $code));
                    $vw->title = "Save On $make->name $model->name Vehicles";
                    $vw->description = "Save On $make->name $model->name Vehicles";
                    $vw->model = $model;
                    $model_years = $this->vehicleYears->getByModel($model->id);
                    $vw->model_years = $model_years;
                    $vw->featuredCar = count($model_years) ? $model_years[0] : null;
                    $vw->geoip = $this->geoip;

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
                $vw->featuredCar = $this->vehicleStyles->getRandom($make->id);
                $vw->geoip = $this->geoip;
                return $vw;
            } else {
                return Redirect::to('/cars');
            }
            
        } else {
            $year = $this->vehicleYears->getByYearMakeModel($one,$two,$three);
            $style = $this->vehicleStyles->find($four);
            if (empty($style))
            {
                $style = $this->vehicleStyles->find($two);
            }
            if (!empty($style))
            {
                $favorited = 0;
                if(Auth::check())
                {
                    $favorites = $this->users->getFavorites(Auth::User()->id, 'VehicleStyle', $style->id);
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
                $vw->assets = $this->vehicleAssets->getByStyle($style->id);
                $vw->model = $this->vehicleModels->find($style->model_id);
                $vw->geoip = $this->geoip;
                $reviews = $this->vehicleStyles->getReviews($style->id);
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

                if(Auth::check())
                {
                    $user = Auth::User();
                    $this->users->view($user, $style);
                }
                else
                {
                    $nonmember = Auth::nonmember();
                    $this->nonmembers->view($nonmember, $style);
                }

                return $vw;
            } else {
                return Redirect::to('/cars');
            }
        }
    }

    public function autoServices($state = null, $city = null)
    {
        /*if(!$state)
            return Redirect::to('/cars/auto-services/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name), 301);*/

        $lowerRedirect = $this->checkLowercase('auto-services', $state, $city);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($state && !$city)
            return $this->cities($state, 'auto-services');

        if(Input::has('chg'))
        {
            $url = '/cars/auto-services/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            return Redirect::to($url);
        }

        if($state && $city)
        {
            $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
            if(!$zipcode)
                return App::abort(404);
            $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
            $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        }

        $code = array();
        $code[] = View::make('soct.jscode.auto-services');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.auto-services')->with('code', implode(' ', $code));
        $subcategory = $this->categories->findBySlug('auto-dealers');
        $vw->subcategory = $subcategory;
        $vw->title = "Save On Service & Lease Specials";
        $vw->description = "Save On Service & Lease Specials";
        $vw->geoip = $this->geoip;
        $vw->page_type = 'auto-services';
        $vw->state = $state;
        $vw->city = $city;

        $category_id = $subcategory->id;
        $city = $this->geoip->city_name;
        $state = $this->geoip->region_name;
        $latitude = $this->geoip->latitude;
        $longitude = $this->geoip->longitude;
        $page = \Input::get('page', 0);
        $limit = \Input::get('limit', 12);
        $filterEntered = false;
        $user = Auth::check() ? Auth::User() : false;
        $show_demo = !$user ? false : $this->users->showDemo($user);
        $results = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, 'coupon', $filterEntered, $category_id, 'null', $page, $limit, 80000);

        if(!empty($user))
            $results['objects'] = $this->users->markClipped($user, $results['objects']);

        $vw->entities = $results['objects'];
        $vw->featuredDealer = $this->franchises->getFeaturedDealer($this->geoip->latitude, $this->geoip->longitude, true);

        $this->trackPremiumView();

        return $vw;
    }

    public function featuredDealers($state = null, $city = null)
    {
        /*if(!$state)
            return Redirect::to('/cars/featured-dealers/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name), 301);*/

        $lowerRedirect = $this->checkLowercase('featured-dealers', $state, $city);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($state && !$city)
            return $this->cities($state, 'featured-dealers');

        if(Input::has('chg'))
        {
            $url = '/cars/featured-dealers/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            return Redirect::to($url);
        }

        if($state && $city)
        {
            $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
            if(!$zipcode)
                return App::abort(404);
            $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
            $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        }

        $code = array();
        $code[] = View::make('soct.jscode.featured-dealers');
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('soct.jscode.search');
        $vw = View::make('soct.featured-dealers')->with('code', implode(' ', $code));
        $vw->title = "Save On Featured Dealers";
        $vw->description = "Save On Featured Dealers";
        $vw->geoip = $this->geoip;
        $vw->page_type = 'featured-dealers';
        $vw->state = $state;
        $vw->city = $city;

        $dealers = $this->franchises->getFeaturedDealers(
            $this->geoip->latitude, 
            $this->geoip->longitude, 
            Input::get('page', 0), 
            Input::get('limit', 25)
        );
        
        //print_r($dealers);
        
        foreach($dealers['objects'] as &$dealer)
        {
            for($j=0; $j<count($dealer->merchant->eager_assets); $j++)
            {
                if($j==0)
                    $dealer->display_image = $dealer->merchant->eager_assets[0]['path'];
                if($dealer->merchant->eager_assets[$j]['name'] == 'logo1')
                    $dealer->display_image = $dealer->merchant->eager_assets[$j]['path'];
            }
        }
        $vw->dealers = $dealers['objects'];

        $this->trackPremiumView();

        return $vw;
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
        $data = array(
            'quoteable_id' => Input::get('usedQuoteVehicle'),
            'first_name' => Input::get('usedQuoteFirst'),
            'last_name' => Input::get('usedQuoteLast'),
            'email' => Input::get('usedQuoteEmail'),
            'phone' => Input::get('usedQuotePhone'),
            'zip' => Input::get('usedQuoteZipcode')
        );
        $parsed = parse_url(Request::header('referer'));
        $vehicle = $this->vehicleEntities->find(Input::get('usedQuoteVehicle'));
        if( ! $vehicle )
        {
            return Redirect::to($parsed['path']);
        }

        $franchise = $this->franchises->findByCompanyMerchant(1, $vehicle->merchant_id);
        if(!Auth::check())
        {
            try
            {
                $user = $this->users->create(array(
                    'email' => Input::get('usedQuoteEmail'),
                    'username' => Input::get('usedQuoteEmail'),
                    'type' => 'Member',
                    'name' => Input::get('usedQuoteFirst').' '.Input::get('usedQuoteLast'),
                    'zipcode' => Input::get('usedQuoteZipcode')
                ));
                Auth::login($user);                
            }
            catch(Exception $e)
            {
                //Unable to create user, might be a repeat
            }
        }

        $data['quoteable_id'] = $vehicle->vendor_inventory_id;
        $data['quoteable_type'] = 'VehicleEntity-'.$vehicle->vendor;
        $data['franchise_id'] = $franchise->id;
        $data['user_id'] = Auth::check() ? Auth::User()->id : 0;
        $data['user_ip'] = SoeHelper::getIp();

        $quote = $this->autoQuotes->create($data);
        if( ! $quote )
        {
            $errors = $this->autoQuotes->errors();
            return Redirect::to($parsed['path'].'?showeid='.$vehicle->id.'&eidtype=usedvehicle')->with(array('errors' => $errors))->withInput();
        }
        return Redirect::to($parsed['path'].'?modal=quoteThanksModal');
    }

    public function usedRss()
    {
        $min_price = Input::get('min_price', null);
        $max_price = Input::get('max_price', null);
        $year = Input::get('year', null);
        $make = Input::get('make', null);
        $model = Input::get('model', null);
        $trim = Input::get('trim', null);
        $zip = Input::get('zipcode', null);
        $radius = Input::get('radius', 75);
        $vin = Input::get('vin', null);
        $description = Input::get('description', 0);
        $images = Input::get('all_images', 0);
        $merchant_id = Input::get('dealer_id', null);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        $state = Input::get('state', null);
        $include_new = Input::get('include_new', 'no');
        if(!$min_price && !$max_price && !$year && !$make && !$model && !$trim && !$zip && !$vin && !$merchant_id && $limit==0 && !$state)
        {
            // No limiting params specified, force hard limit on results to prevent a full DB dump.
            $limit = 25000;
        }
        $usedRss = new UsedRss;
        $xml = $usedRss->build($min_price, $max_price, $year, $make, $model, $trim, $zip, $radius, $vin, $description, $images, $merchant_id, $page, $limit, $state, $include_new);
        $header['Content-Type'] = 'application/xml';
        return Response::make($xml->asXML(), 200, $header);
    }

    public function newRss()
    {
        $min_price = Input::get('min_price', null);
        $max_price = Input::get('max_price', null);
        $year = Input::get('year', null);
        $make = Input::get('make', null);
        $model = Input::get('model', null);
        $body = Input::get('type', null);
        $images = Input::get('all_images', 0);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        $newRss = new NewRss;
        $xml = $newRss->build($min_price, $max_price, $year, $make, $model, $body, $images, $page, $limit);
        $header['Content-Type'] = 'application/xml';
        return Response::make($xml->asXML(), $xml ? 200 : 500, $header);
    }

    public function netlmsJs()
    {
        $vin = Input::get('v');
        $vehicle = $this->vehicleEntities->findByVinAndVendor($vin, 'detroit_trading');
        if(!$vehicle)
            App::abort(404, 'VIN Not Found');

        $vw = View::make('soct.netlms-js');
        $vw->vehicle = $vehicle;
        $vw->viewUrl = URL::abs('/cars/affiliate-view');
        $vw->postUrl = Config::get('integrations.netlms.base_url').'/api/v2/lead';
        $vw->quoteUrl = URL::abs('/cars/affiliate-quote');
        $response = Response::make($vw, 200);
        $response->header('Content-Type', 'application/javascript');
        return $response;
    }

    public function newNetlmsJs()
    {
        $vehicle_id = Input::get('v', '');
        $vehicle = $this->vehicleStyles->find($vehicle_id);
        $makes = $this->vehicleMakes->getActiveMakes();
        $models = $this->vehicleModels->getNewByMake($vehicle ? $vehicle->make_slug : '');
        $vw = View::make('soct.new-netlms-js');
        $vw->makes = $makes;
        $vw->vehicle = $vehicle;
        $vw->models = $models;
        $vw->postUrl = Config::get('integrations.netlms.base_url').'/api/v2/lead';
        $vw->postDealerUrl = URL::abs('/api/v2/vehicle-style/new-auto-quote-dealers');
        $vw->quoteUrl = URL::abs('/cars/affiliate-new-quote');
        $response = Response::make($vw, 200);
        $response->header('Content-Type', 'application/javascript');
        return $response;
    }

    public function affiliateView()
    {
        $data = Input::getContent();
        $data = json_decode($data, true);
        if(!$data)
            return;
        $vehicle = $this->vehicleEntities->find($data['vehicle_entity_id']);
        if(!$vehicle)
            return;
        $merchant = $this->merchants->find($vehicle->merchant_id);
        $location = $this->locations->find($vehicle->location_id);
        $category = $this->categories->find($merchant->category_id);
        $subcategory = $this->categories->find($merchant->subcategory_id);
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify('Affiliate-'.time());
        $mp->track('Vehicle Entity Impression', array(
            'VehicleEntityId' => $vehicle->id,
            'Environment' => \App::environment(),
            'MerchantId' => $vehicle->merchant_id,
            'MerchantName' => $merchant->display,
            'MerchantNameAddress' => $merchant->display.' - '.$location->address,
            'LocationId' => $location->id,
            'FranchiseId' => $location->franchise_id,
            'Category' => !empty($category) ? $category->name : '',
            'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
            'UserType' => 'AffiliateUser',
            'Referrer' => parse_url(URL::previous(), PHP_URL_HOST)
        ));

        $data = array(
            'user_id' => 0,
            'nonmember_id' => 0,
            'location_id' => $vehicle->location_id,
            'merchant_id' => $vehicle->merchant_id,
            'vehicle_entity_id' => $vehicle->id
        );

        $view = $this->userImpressions->create($data);
    }

    public function affiliateQuote()
    {
        $data = Input::getContent();
        $data = json_decode($data, true);
        if(!$data)
        {
            return;
        }
        $zipcode = $this->zipcodes->findByZipcode($data['zipcode']);
        $vehicle = $this->vehicleEntities->find($data['vehicle_entity_id']);
        if(!$vehicle)
            return;
        $merchant = $this->merchants->find($vehicle->merchant_id);
        $franchise = $this->franchises->findByCompanyMerchant(1, $merchant->id);
        $category = $this->categories->find($merchant->category_id);
        $subcategory = $this->categories->find($merchant->subcategory_id);
        $user = $this->users->findByEmail($data['email']);
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify($data['email']);
        $mp->track('Auto Quote Submission', array(
            '$city' => ($zipcode ? $zipcode->city : ''),
            'Environment' => \App::environment(),
            'FranchiseId' => $franchise->id,
            'MerchantId' => empty($franchise) ? 0 : $franchise->merchant_id,
            'MerchantName' => empty($merchant) ? '' : $merchant->display,
            '$region' => ($zipcode ? $zipcode->state : ''),//$user->state,
            'Category' => !empty($category) ? $category->name : '',
            'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
            'Year' => $vehicle->year,
            'Make' => $vehicle->make,
            'Model' => $vehicle->model,
            'Type' => 'Used',
            'Referrer' => parse_url(URL::previous(), PHP_URL_HOST)
        ));
    }

    public function affiliateNewQuote()
    {
        $data = Input::getContent();
        $data = json_decode($data, true);
        if(!$data)
        {
            return;
        }
        $zipcode = $this->zipcodes->findByZipcode($data['zipcode']);
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify($data['email']);
        $mp->track('Auto Quote Submission', array(
            '$city' => ($zipcode ? $zipcode->city : ''),
            'Environment' => \App::environment(),
            'FranchiseId' => 0,
            'MerchantId' => 0,
            'MerchantName' => '',
            '$region' => ($zipcode ? $zipcode->state : ''),
            'Category' => '',
            'Subcategory' => '',
            'Year' => $data['year'],
            'Make' => $data['make'],
            'Model' => $data['model'],
            'Type' => 'New',
            'Referrer' => parse_url(URL::previous(), PHP_URL_HOST)
        ));
    }

    protected function checkState($string)
    {
        $string = strtoupper($string);
        $countries = SoeHelper::states();
        $found = false;
        $stateName = '';
        // Check if this is a state
        foreach($countries as $country => $data)
        {
            foreach($data['states'] as $abbr => $name)
            {
                if($string == $abbr)
                {
                    $found = true;
                    $stateName = $name;
                }
            }
        }

        return $found ? $stateName : $found;
    }

    protected function checkLowercase($type, $state, $city, $year = null, $make = null, $model = null, $dealer = null)
    {
        if(ctype_upper($state))
        {
            $redirect = '/cars/'.$type.'/'.strtolower($state);
            $redirect .= $city != '' ? '/'.SoeHelper::getSlug($city) : '';
            $redirect .= $year != '' ? '/'.$year : '';
            $redirect .= $make != '' ? '/'.$make : '';
            $redirect .= $model != '' ? '/'.$model : '';
            $redirect .= $dealer != '' ? '/'.$dealer : '';
            return Redirect::to($redirect, 301);
        }

        return false;
    }

    protected function getDisplayImage($vehicles)
    {
        foreach($vehicles['objects'] as &$vehicle)
        {
            $images = explode(',', str_replace('|', ',', $vehicle->image_urls));
            if(count($images))
                $vehicle->display_image = $images[0];
            else
            {
                // TODO: Add placeholder car image.
                $vehicle->display_image = 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg';
            }
            if($vehicle->display_image == '')
                $vehicle->display_image = 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg';
            else {
                // Test if image is broken
                $handle = curl_init($vehicle->display_image);
                curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                /* Get the HTML or whatever is linked in $url. */
                $response = curl_exec($handle);
                /* Check for 404 (file not found). */
                $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                if($httpCode == 404) {
                   $vehicle->display_image = 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg';
                }
            }
        }

        return $vehicles;
    }

    protected function trackPremiumView()
    {
        if($this->premium['stats']['returned'] != 0)
        {
            $person = $this->personFactory->make();
            $viewable = $this->viewableFactory->make('Advertisement', $this->premium['objects'][0]->id);
            if($person && $viewable)
                $viewable->view($person);
        }
    }

}
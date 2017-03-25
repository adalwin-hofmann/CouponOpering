<?php

class CouponsController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AdvertisementRepositoryInterface $advertisements,
        AssetRepositoryInterface $assets,
        CategoryRepositoryInterface $categories,
        CityImageRepositoryInterface $cityImages,
        CompanyRepositoryInterface $companies,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $features,
        FranchiseRepositoryInterface $franchises,
        LocationRepositoryInterface $locations,
        MerchantRepositoryInterface $merchants,
        OfferRepositoryInterface $offers,
        UserLocationRepositoryInterface $userLocations,
        UserRepositoryInterface $users,
        UsedVehicleRepositoryInterface $usedVehicles,
        VehicleEntityRepositoryInterface $vehicleEntities,
        VehicleStyleRepositoryInterface $vehicleStyles,
        ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->advertisements = $advertisements;
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
        $this->offers = $offers;
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->userLocations = $userLocations;
        $this->users = $users;
        $this->zipcodes = $zipcodes;
        $this->usedVehicles = $usedVehicles;
        $this->vehicleEntities = $vehicleEntities;
        $this->vehicleStyles = $vehicleStyles;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        //$this->geoip->region_name = strtolower($this->geoip->region_name);
        parent::__construct();
    }

    /**
     * This is the base route for coupons.
     */
    public function getIndex($state = '', $city = '', $category = '', $subcategory = '', $merchant = '', $location = '')
    {
        if($state == '')
        {
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/all', 301);
        }
        else
        {
            if(!$this->checkState($state))
                return $this->handleCouponsRedirects($state, $city, $category, $subcategory, $merchant, $location);
        }

        $lowerRedirect = $this->checkLowercase('coupons', $state , $city , $category , $subcategory , $merchant , $location);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($city == '')
        {
            return $this->couponsIn($state);
        }
        
        if(Input::has('chg'))
        {
            $url = '/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $category ? '/'.$category : '';
            $url .= $subcategory ? '/'.$subcategory : '';
            $url .= $merchant ? '/'.$merchant : '';
            $url .= $location ? '/'.$location : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            
            return Redirect::to($url.$eid);
        }

        // Handle redirect for old /coupons/in/state route
        if(strtolower($state) == 'in' && (strlen($city) == 2 || strlen($category) == 2))
        {
            if($category)
                return Redirect::to('/coupons/'.$category.'/'.$city.'/all', 301);
            else
                return Redirect::to('/coupons/'.$city, 301);
        }

        $slugged = SoeHelper::getSlug($city);
        if($slugged != $city)
        {
            $url = '/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $category ? '/'.$category : '';
            $url .= $subcategory ? '/'.$subcategory : '';
            $url .= $merchant ? '/'.$merchant : '';
            $url .= $location ? '/'.$location : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            
            return Redirect::to($url.$eid, 301);
        }

        $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
        if(!$zipcode)
            App::abort(404);
        $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));

        if($category == '')
        {
            return $this->category('all');
        }
        if($subcategory == '')
        {
            return $this->category($category);
        }
        if($merchant == '')
        {
            return $this->category($subcategory);
        }
        $subcategoryLookUp = $this->categories->findBySlug($subcategory);
        if (empty($subcategoryLookUp))
        {
            $location = $merchant;
            $merchant = $subcategory;
            $subcategory = 0;
        }
            
        return $this->merchant($category, $subcategory, $merchant, $location, $state, $city);
    }

    public function oldCoupons($one = '', $two = '', $three = '', $four = '', $five = '', $six = '')
    {
        $url = '/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
        if($two != '')
        {
            $category = $this->categories->findBySlug(SoeHelper::getSlug($two));
            if($category)
                $url .= '/'.$category->slug;
        }
        
        if($four != '')
        {
            $subcategory = $this->categories->findBySlug(SoeHelper::getSlug($four));
            if($subcategory)
                $url .= '/'.$subcategory->slug;
        }
        
        if($two == '')
        {
            $category = $this->categories->findBySlug(SoeHelper::getSlug($one));
            if($category)
                $url .= '/'.$category->slug;
        }

        return Redirect::to($url, 301);
    }

    public function dailydeals($state = '', $city = '', $category = '', $subcategory = '', $merchant = '', $location = '')
    {
        if($state == '')
        {
            return Redirect::to('/dailydeals/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/all', 301);
        }
        else
        {
            if(!$this->checkState($state))
                return $this->handleDealsRedirects($state, $city, $category, $subcategory, $merchant, $location);
        }

        $lowerRedirect = $this->checkLowercase('dailydeals', $state , $city , $category , $subcategory , $merchant , $location);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($city == '')
        {
            return $this->couponsIn($state, 'dailydeal');
        }
        
        if(Input::has('chg'))
        {
            $url = '/dailydeals/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $category ? '/'.$category : '';
            $url .= $subcategory ? '/'.$subcategory : '';
            $url .= $merchant ? '/'.$merchant : '';
            $url .= $location ? '/'.$location : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            
            return Redirect::to($url.$eid);
        }
        
        $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
        if(!$zipcode)
            App::abort(404);
        $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        if($category == '')
        {
            return $this->category('all', 'dailydeal');
        }
        if($subcategory == '')
        {
            return $this->category($category, 'dailydeal');
        }
        if($merchant == '')
        {
            return $this->category($subcategory, 'dailydeal');
        }
        $subcategoryLookUp = $this->categories->findBySlug($subcategory);
        if (empty($subcategoryLookUp))
        {
            $location = $merchant;
            $merchant = $subcategory;
            $subcategory = 0;
        }

        return $this->merchant($category, $subcategory, $merchant, $location);
    }

    public function contests($state = '', $city = '', $category = '', $subcategory = '', $merchant = '', $location = '')
    {
        if($state == '')
        {
            return Redirect::to('/contests/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/all', 301);
        }
        else
        {
            if(!$this->checkState($state))
                return $this->handleContestsRedirects($state, $city, $category, $subcategory, $merchant, $location);
        }

        $lowerRedirect = $this->checkLowercase('contests', $state , $city , $category , $subcategory , $merchant , $location);
        
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if($city == '')
        {
            return $this->couponsIn($state, 'contest');
        }
        if(Input::has('chg'))
        {
            $url = '/contests/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $category ? '/'.$category : '';
            $url .= $subcategory ? '/'.$subcategory : '';
            $url .= $merchant ? '/'.$merchant : '';
            $url .= $location ? '/'.$location : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            return Redirect::to($url.$eid);
        }
        $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
        if(!$zipcode)
            App::abort(404);
        $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, SoeHelper::unSlug($city), $state);
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        if($category == '')
        {
            return $this->category('all', 'contest');
        }
        if($subcategory == '')
        {
            return $this->category($category, 'contest');
        }
        if($merchant == '')
        {
            return $this->category($subcategory, 'contest');
        }
        $subcategoryLookUp = $this->categories->findBySlug($subcategory);
        if (empty($subcategoryLookUp))
        {
            $location = $merchant;
            $merchant = $subcategory;
            $subcategory = 0;
        }

        return $this->merchant($category, $subcategory, $merchant, $location);
    }

    public function franchiseRss($location_id)
    {
        $location_only = Input::get('location', 1);
        $franchiseRss = new FranchiseRss;
        $xml = $franchiseRss->build($location_id, $location_only);
        $header['Content-Type'] = 'application/xml';
        return Response::make($xml->asXML(), $xml ? 200 : 500, $header);
    }

    public function couponsRss($type_id)
    {
        $couponsRss = new CouponsRss;
        $xml = $couponsRss->build($type_id);
        $header['Content-Type'] = 'application/xml';
        return Response::make($xml->asXML(), $xml ? 200 : 500, $header);
    }
	
    public function carDealersRss()
    {
        $carDealersRss = new CarDealersRss;
        $xml = $carDealersRss->build();
        $header['Content-Type'] = 'application/xml';
        return Response::make($xml->asXML(), $xml ? 200 : 500, $header);
    }

    protected function handleContestsRedirects($one, $two, $three, $four, $five, $six)
    {
        $eid = '';
        if($entity_id = Input::get('showeid'))
        {
            $entity = $this->entities->find($entity_id);
            if($entity && $entity->is_active)
                $eid = '?showeid='.$entity_id;
        }       
        if($two == '')
        {
            return Redirect::to('/contests/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.$eid, 301);
        }
        if($three == '')
        {
            return Redirect::to('/contests/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.'/'.$two.$eid, 301);
        }
        return Redirect::to('/');
    }

    protected function handleDealsRedirects($one, $two, $three, $four, $five, $six)
    {
        $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
        if($two == '')
        {
            return Redirect::to('/dailydeals/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.$eid, 301);
        }
        if($three == '')
        {
            return Redirect::to('/dailydeals/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.'/'.$two.$eid, 301);
        }
        return Redirect::to('/');
    }

    protected function handleCouponsRedirects($one, $two, $three, $four, $five, $six)
    {
        $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
        if($two == '')
        {
            if($one == 'home-services')
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/home-improvement'.$eid, 301);
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.$eid, 301);
        }
        if($three == '')
        {
            if($one == 'home-services')
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/home-improvement/'.$two.$eid, 301);
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.'/'.$two.$eid, 301);
        }
        if($four == '')
        {
            if($one == 'home-services')
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/home-improvement/'.$two.'/'.$three.$eid, 301);
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.'/'.$two.'/'.$three.$eid, 301);
        }

        return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$one.'/'.$two.'/'.$three.'/'.$four.$eid, 301);
    }

    protected function couponsIn($state, $type = 'coupon')
    {
        $sohi = $this->features->findByName('home_improvement');
        $code = array();
        $code[] = View::make('home.jscode.country-cities');
        $found = false;
        $stateName = '';
        // Check if this is a state
        $found = $this->checkState($state);
        $stateName = $found;

        // This is a state, redirect to cities page.
        $city_image = $this->cityImages->findByState($state);
        $vw = View::make('home.country-cities')->with('code', implode(' ', $code));
        $zipcode = $this->zipcodes->getByCityState($this->geoip->city_name, $this->geoip->region_name);
        if(!empty($zipcode))
        {
            $vw->userZip = $zipcode->zipcode;
        } else {
            // Default to Troy's Zip
            $vw->userZip = '48084';
        }
        $vw->title = "Find Local Coupons and Deals in ".strtoupper($state)." - SaveOn";
        $vw->description = "Find Local Coupons and Deals in ".strtoupper($state)." - SaveOn";
        $vw->subheader = ($city_image->sub_heading) ? SoeHelper::cityStateReplace($city_image->sub_heading, $geoip) : '';
        $vw->active = 0;
        $vw->stateName = $stateName;
        $vw->state = $state;
        $cities = $this->locations->getTopCitiesByState($state);
        $vw->cities = $cities;
        //echo $cities->location_count;
        $stateLocation = $cities['top_city'];
        //$stateLocation = $this->locations->getTopCityByState($state)['objects'][0];
        
        $vw->city_image = $city_image;
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $geoip = $this->geoip;
        $vw->geoip = $geoip;

        // Load initial entities
        $category_id = 0;
        $city = $stateLocation->city; //\Input::get('city');
        $state = $stateLocation->state; //\Input::get('state');
        $latitude = $stateLocation->latitude; //\Input::get('latitude');
        $longitude = $stateLocation->longitude; //\Input::get('longitude');
        $page = 0;
        $limit = 3;
        $filterEntered = false;
        $user = Auth::check() ? Auth::User() : false;
        $show_demo = !$user ? false : $this->users->showDemo($user);
        $radius = 0;

        $dailydeals = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, 'dailydeal', $filterEntered, 0, null, $page, $limit, $radius);
        if(!empty($user))
            $dailydeals['objects'] = $this->users->markClipped($user, $dailydeals['objects']);
        $vw->dailydeals = $dailydeals['objects'];

        $contests = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, 'contest', $filterEntered, 0, null, $page, $limit, $radius);
        if(!empty($user))
            $contests['objects'] = $this->users->markClipped($user, $contests['objects']);
        $vw->contests = $contests['objects'];

        $soct = $this->vehicleEntities->search(
            null, 
            null, 
            null, 
            null, 
            null, 
            null,
            0,
            3,
            'rand',
            null,
            null,
            $state,
            false
        );
        $soct = $this->getDisplayImage($soct);
        $vw->soct = $soct['objects'];

        $travel = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, 'coupon', $filterEntered, 5, null, $page, $limit, $radius);
        if(!empty($user))
            $travel['objects'] = $this->users->markClipped($user, $travel['objects']);
        $travel['objects'] = $this->entities->addLocations($travel['objects']);
        $vw->travel = $travel['objects'];

        $food = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, 'coupon', $filterEntered, 4, null, $page, $limit, $radius);
        if(!empty($user))
            $food['objects'] = $this->users->markClipped($user, $food['objects']);
        $food['objects'] = $this->entities->addLocations($food['objects']);
        $vw->food = $food['objects'];

        $states = SoeHelper::states();
        $vw->states = $states['USA']['states'];

        $vw->type = $type;

        return $vw;
    }

    protected function category($category_slug = null, $type = 'coupon')
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.category');
        $vw = View::make('home.category')->with('code', implode(' ', $code));

        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $entity = $this->entities->find(Input::get('showeid', 0));
        if($entity)
        {
            if ($entity->entitiable_type != 'Contest')
            {
                $location = $this->locations->find($entity->location_id);
                if($location && $entity->is_active)
                    return Redirect::to(URL::abs('/').'/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$entity->category_slug.'/'.$entity->subcategory_slug.'/'.$entity->merchant_slug.'/'.$entity->location_id.'?showeid='.Input::get('showeid'), 301);
                else
                    return Redirect::to(URL::abs('/').'/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all', 301);
            } 
            elseif ((strtotime($entity->expires_at) < time()) && ($type == 'contest')) 
            {
                return Redirect::to(URL::abs('/').'/contests/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all', 301);
            }
        }
        $vw->categories = $this->categories->getByParentId(0);
        $category = $this->categories->findBySlug($category_slug);
        $zipcode = $this->zipcodes->getByCityState($this->geoip->city_name, $this->geoip->region_name);
        if(!empty($zipcode))
        {
            $vw->userZip = $zipcode->zipcode;
        } else {
            // Default to Troy's Zip
            $vw->userZip = '48084';
        }
        $vw->geoip = $this->geoip;
        $vw->category = $category;
        $vw->category_id = empty($category) ? 0 : $category->id;
        $vw->category_slug = empty($category) ? 0 : $category->slug;
        $vw->parent_id = empty($category) ? 0 : $category->parent_id;
        $parent_category = Category::find(empty($category) ? 0 : $category->parent_id);
        $vw->parent_slug = (empty($parent_category)) ? 0 : $parent_category->slug;
        $vw->parent_name = (empty($parent_category)) ? 0 : $parent_category->name;
        $vw->active = empty($category) ? 0 : ($category->parent_id == 0 ? $category->id : $category->parent_id);
        $vw->type = $type;
        if(!empty($category))
            $vw->subcategories = $this->categories->getByParentSlug((empty($parent_category))?$category->slug:$parent_category->slug);
        $radius = 0;
        $ad_type = $type;
        if ($type == 'coupon')
        {
            $displayType = "Coupons";
            $ad_type = 'offer';
        } 
        elseif ($type == 'dailydeal')
        {
            $displayType = "Deals";
        } 
        else 
        {
            $displayType = "Contests";
            // Get Win5k
            $entity = SOE\DB\Entity::where('name','=','win5k')->where('state','=',$this->geoip->region_name)->where('is_active', '=', '1')->first();
            $vw->win5k = $entity;
            $radius = 80000;
        }
        if (empty($category))
        {
            //$title = "SaveOn ".$displayType." in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name;
            $title = ucwords(strtolower($this->geoip->city_name))." Coupons Offers Printable Free Online Coupons - SaveOn";
            $description = "SaveOn ".$displayType." in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name;
            if ($type == 'coupon')
            {
                // Coupons
                $title = ucwords(strtolower($this->geoip->city_name))." Coupons Offers Printable Free Online Coupons - SaveOn";
            } 
            elseif ($type == 'dailydeal')
            {
                // Daily Deals
                $title = ucwords(strtolower($this->geoip->city_name))." Coupons Offers Printable Free Online Coupons - SaveOn";
            } 
            else 
            {
                // Contests
                $title = 'Contests - Instant Win Sweepstakes - '.ucwords(strtolower($this->geoip->city_name))." ".$this->geoip->region_name.' | Save On';
                $description = 'Check out SaveOn.com '.date("Y").' online contests and instant win sweepstakes for '.ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name.'.  Every contest in '.ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name.' from SaveOn makes you a savings instant winner.';
            }
        } else {
            $title = ($category->title != '') ? $category->title : $category->name.' in '.ucwords(strtolower($this->geoip->city_name)).', '.$this->geoip->region_name;
            $description = ($category->description != '')?$category->description:$category->name." coupons in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name.", ".$category->name." discounts. Restaurant coupons, restaurant deals in ".ucwords(strtolower($this->geoip->city_name)).", ".$this->geoip->region_name;
        }
        $title = SoeHelper::cityStateReplace($title, $this->geoip);
        $description = SoeHelper::cityStateReplace($description, $this->geoip);
        $vw->displayType = $displayType;
        $vw->title = $title;
        $vw->description = $description;
        $vw->subheader = (!empty($category) && $type == 'coupon') ? SoeHelper::cityStateReplace($category->sub_heading, $geoip) : '';

        // Load initial entities
        $category_id = $category ? $category->id : 0; //\Input::get('category_id');
        $city = $this->geoip->city_name; //\Input::get('city');
        $state = $this->geoip->region_name; //\Input::get('state');
        $latitude = $this->geoip->latitude; //\Input::get('latitude');
        $longitude = $this->geoip->longitude; //\Input::get('longitude');
        $page = \Input::get('page', 0);
        $limit = \Input::get('limit', 12);
        $filterEntered = false;
        $user = Auth::check() ? Auth::User() : false;
        $show_demo = !$user ? false : $this->users->showDemo($user);
        $results = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, $type, $filterEntered, $category_id, null, $page, $limit, $radius);

        if(!empty($user))
            $results['objects'] = $this->users->markClipped($user, $results['objects']);

        $vw->entities = $results['objects'];

        if (!count($results['objects']))
        {
            $relatedResults = $this->entities->getByCategory($city, $state, $latitude, $longitude, $show_demo, $type, $filterEntered, $category ? ($category->parent_id == 0 ? $category->id : $category->parent_id) : null, null, $page, 3, $radius);
            if(!empty($user))
                $relatedResults['objects'] = $this->users->markClipped($user, $relatedResults['objects']);
            $vw->relatedEntities = $relatedResults['objects'];
        }

        $category_ads = $this->advertisements->search($ad_type, 'basic', 'menu', null, $category ? ($category->parent_id == 0 ? $category->id : $category->parent_id) : null, ($category && $category->parent_id != 0) ? $category->id : null, $show_demo, 0, 1);
        if($category_ads['stats']['returned'] != 0)
        {
            $person = $this->personFactory->make();
            $viewable = $this->viewableFactory->make('Advertisement', $category_ads['objects'][0]->id);
            if($person && $viewable)
                $viewable->view($person);
        }
        $vw->category_ads = $category_ads;

        if ($type == 'contest')
        {
            $vw->width = 'full';
        } else {
            $vw->width = 'not-full';
        }

        return $vw;
    }

    protected function merchant($category_slug, $subcategory_slug = null, $merchant_slug, $locationid = '', $state = '', $city = '')
    {
        // Handle Location Coupons

        $location = $this->locations->find($locationid);
        if(!$location)
        {
            $location = $this->merchants->findNearestLocationBySlug($merchant_slug, $this->geoip->latitude, $this->geoip->longitude);
            if(!$location)
            {
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$category_slug.($subcategory_slug ? '/'.$subcategory_slug : ''), 301);
            }
        }

        if(strtolower(SoeHelper::unSlug($city)) == 'mountain view' && strtoupper($state) == 'CA')
        {
            if(strtolower($location->city) != 'mountain view')
                return Redirect::to('/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$category_slug.($subcategory_slug ? '/'.$subcategory_slug : '').'/'.$merchant_slug, 301);
        }

        $merchant = $this->merchants->with('makes')->find($location->merchant_id);

        $make_ids = '';
        foreach($merchant->makes as $make)
        {
            $make_ids .= $make->id.',';
        }
        $make_ids = trim($make_ids,',');

        $franchise = $this->franchises->find($location->franchise_id);
        if(!$franchise)
            App::abort(404);

        if(!$franchise->is_active)
        {
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$category_slug.($subcategory_slug ? '/'.$subcategory_slug : ''), 301);
        }
        if(!$location->is_active)
        {
            return Redirect::to('/directions/'.$merchant_slug, 301);
        }

        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make('Location', $location->id);
        if($person && $viewable)
            $viewable->view($person);

        $reviews = $this->locations->getReviews($location);
        $is_reviewed = 0;
        $is_favorited = 0;
        if(Auth::check())
        {
            $is_reviewed = $this->users->hasReviewed($location->id, 'Location', Auth::User());
            $is_favorited = $this->users->hasFavorited($location->id, 'Location', Auth::User());
        }

        $code = array();
        $code[] = View::make('home.jscode.masonry');

        $entity = $this->entities->find(Input::get('showeid', 0));
        $eidType = Input::get('eidtype', '');
        if($entity && $eidType != 'usedquote')
        {

            if (strtotime($entity->expires_at) < time())
            {
                return Redirect::to(URL::abs('/').'/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$entity->category_slug.'/'.$entity->subcategory_slug.'/'.$entity->merchant_slug.'/'.$entity->location_id, 301);
            }
        }

        if($entity && $eidType != 'usedquote' && $entity->entitiable_type == 'Offer')
        {
            //$code[] = View::make('home.jscode.merchant');
            $code[] = View::make('home.jscode.offer');
            if($franchise->is_dealer)
            {
                $code[] = View::make('soct.jscode.search');
            }
            $vw = View::make('home.offer')->with('code', implode(' ', $code));
            $vw->entity = $entity;
            $offer = $this->offers->find($entity->entitiable_id);
            $vw->offer = $offer;

        }
        elseif ($merchant->page_version == 1)
        {
            $code[] = View::make('home.jscode.merchant');
            if($franchise->is_dealer)
            {
                $code[] = View::make('soct.jscode.search');
            }
            $vw = View::make('home.merchant')->with('code', implode(' ', $code));
        }
        elseif (strpos($_SERVER['REQUEST_URI'],'/schools/') !== false)
        {
            /*if (strpos($_SERVER['REQUEST_URI'],'/coupons/') !== false)
            {
                return Redirect::to(URL::abs('/').'/schools/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$category_slug.'/'.$subcategory_slug.'/'.$location->merchant_slug.'/'.$location->id, 301);
            }*/

            $code[] = View::make('home.jscode.merchant-v2');
            if($franchise->is_dealer)
            {
                $code[] = View::make('soct.jscode.search');
            }
            $vw = View::make('home.merchant-school')->with('code', implode(' ', $code));
        }
        elseif ($merchant->page_version == 2)
        {
            $code[] = View::make('home.jscode.merchant-v2');
            if($franchise->is_dealer)
            {
                $code[] = View::make('soct.jscode.search');
            }
            $vw = View::make('home.merchant-v2')->with('code', implode(' ', $code));
        }
        
        $vw->location = $location;
        if($location->facebook != '')
            $merchant->facebook = $location->facebook;
        if($location->twitter != '')
            $merchant->twitter = $location->twitter;
        $vw->merchant = $merchant;
        $vw->make_ids = $make_ids;
        $vw->franchise = $franchise;
        $vw->is_reviewed = $is_reviewed;
        $vw->is_favorited = $is_favorited;
        $locations_count = $this->merchants->locationsCount($merchant);
        $vw->locationCount = $locations_count;

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

        $zipcode = $this->zipcodes->findByZipcode($location->zip);
        if(!$zipcode)
        {
            $zipcode = $this->zipcodes->getByCityState($location->city, $location->state);
            if(!$zipcode)
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$category_slug.($subcategory_slug ? '/'.$subcategory_slug : ''));
        }
        $vw->locationZipcode = $zipcode;

        $category = $this->categories->find($merchant->category_id);
        $vw->catName = $category ? $category->name : '';
        $vw->catSlug = $category ? $category->slug : '';
        $vw->catId = $category ? $category->id : 0;

        $subcategory = $this->categories->find($merchant->subcategory_id);
        $vw->subcatName = $subcategory ? $subcategory->name : '';
        $vw->subcatSlug = $subcategory ? $subcategory->slug : '';
        $vw->subcatId = $subcategory ? $subcategory->id : 0;

        $vw->subheader = SoeHelper::merchantReplacements($location->subheader, $location, $merchant, $category, $subcategory);

        $vw->logo = $location->is_logo_specific ? $this->assets->getLocationLogo($location) : $this->assets->getLogo($merchant);
        $vw->banner = $location->is_banner_specific ? $this->assets->getLocationBanner($location) : $this->assets->getBanner($merchant);
        $vw->images = $location->is_about_specific ? $this->assets->getLocationImages($location) : $this->assets->getMerchantImages($merchant);
        $vw->videos = $location->is_video_specific ? $this->assets->getLocationVideos($location) : $this->assets->getMerchantVideos($merchant);
        $vw->pdfs = $location->is_pdf_specific ? $this->assets->getLocationPdfs($location) : $this->assets->getMerchantPdfs($merchant);
        $vw->reviews = $reviews;
        $vw->hours = $this->locations->getHours($location);
        //$vw->similar = Entity::getSimilar($merchant, Auth::check() ? Auth::User() : Auth::Nonmember());
        $vw->user = Auth::check() ? Auth::User() : Auth::Nonmember();
        $quote_control = $this->features->findByName('master_quotes_control');
        $quote_control = empty($quote_control) ? 0 : $quote_control->value;
        $detroit_quote_control = $this->features->findByName('detroit_quotes_only');
        $detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
        if($detroit_quote_control)
        {
            $distance = GeometryHelper::getDistance($this->geoip->latitude, $this->geoip->longitude, 42.38, -83.10);
            $detroit_quote_control = ($distance < $detroit_quote_control && $this->geoip->region_name == 'MI') ? 1 : 0;
            $vw->quote_control = $quote_control && $detroit_quote_control;
        }
        else
        {
            $vw->quote_control = $quote_control;
        }

        $geoip = json_decode(GeoIp::getGeoIp('json'));

        $company = $this->companies->find($location->company_id);
        if($company)
        {
            if($company->logo_image != "")
            {
                $vw->company = $company;
            }
        }
        $page_title = $location->page_title ? $location->page_title : $merchant->page_title;
        $meta_description = $location->meta_description ? $location->meta_description : $merchant->meta_description;
        if($entity && $eidType != 'usedquote' && $entity->entitiable_type == 'Offer')
        {
            $vw->title = $entity->name." - ".$merchant->display." in ".$location->city.", ".$location->state." | Coupons to SaveOn ". ($subcategory && $category ? $subcategory->name." and " .$category->name : '');
            if(strlen($offer->description) > 50 && $entity->entitiable_type == 'Offer')
                $meta_description = (strlen(strip_tags($offer->description)) > 140) ? substr(strip_tags($offer->description),0,140).'...' : strip_tags($offer->description);
        } else {
            $vw->title = $page_title ==''? $merchant->display." in ".$location->city.", ".$location->state." | Coupons to SaveOn ". ($subcategory && $category ? $subcategory->name." and " .$category->name : ''): SoeHelper::cityStateReplace($page_title, $this->geoip);
        }
        
        $vw->description = $meta_description ==''? "Free printable ".$merchant->display." coupons &amp; discounts. Find the deals you need to cut back on ".($category ? $category->name.' ' : '')." expenses at SaveOn." : SoeHelper::cityStateReplace($meta_description, $this->geoip);

        $special_merchant = '';
        $vehicles = array('objects' => array(), 'stats' => array('total' => 0, 'page' => 0, 'take' => 0));
        $new = array('objects' => array(), 'stats' => array('total' => 0, 'page' => 0, 'take' => 0));
        if($franchise->is_dealer)
        {
            $special_merchant = 'soct';
            $vehicles = $this->vehicleEntities->getByMerchant(
                $merchant->id,
                Input::get('used_page', 0),
                12
            );
            foreach($vehicles['objects'] as &$vehicle)
            {
                $images = explode('|', $vehicle->image_urls);
                if(count($images))
                    $vehicle->display_image = $images[0];
                else
                    $vehicle->display_image = '';
            }

            $new = $this->vehicleStyles->search(
                null,
                $make_ids,
                null,
                null,
                null,
                0,
                12,
                'rand',
                null
            );
            foreach($new['objects'] as &$vehicle)
            {
                if(count($vehicle->displayImage))
                    $vehicle->display_image = $vehicle->displayImage[0]->path;
                else
                {
                    $image = count($vehicle->assets) ? $vehicle->assets[0]['path'] : '';
                    $vehicle->display_image = $image;
                }
            }
            if(!$this->assets->getLogo($merchant) && count($vehicles['objects']))
            {
                $vw->logo = new stdClass();
                $vw->logo->path = $vehicles['objects'][0]->display_image;
            }
        }
        $vw->vehicles = $vehicles;
        $vw->new = $new;
        $vw->special_merchant = $special_merchant;
        $vw->new_car_leads = $franchise->is_new_car_leads;
        $vw->used_car_leads = $franchise->is_new_car_leads;
        $vw->franchise = $franchise;

        $vw->geoip = $this->geoip;

        $page = \Input::get('page', 0);
        $show_demo = Auth::check() ? $this->users->showDemo(Auth::User()) : false;
        $entities = $this->locations->getEntities($location, $show_demo, $page, 12);

        $expired_default = $this->features->findByName('expired_default');
        $vw->expired_default = empty($expired_default) ? 0 : $expired_default->value;
        $related = array('objects' => array(), 'stats' => array('total' => 0, 'page' => 0, 'take' => 0));
        $expired = array('objects' => array(), 'stats' => array('total' => 0, 'page' => 0, 'take' => 0));
        if($entities['stats']['total'] == 0)
        {
            $page = 0;
            $related = $this->entities->getByCategory($this->geoip->city_name, $this->geoip->region_name, 
                $this->geoip->latitude, $this->geoip->longitude, $show_demo, 
                'coupon', false, $merchant->category_id, null, 
                $page, 3, 0);

            if(Auth::check())
            {
                $related['objects'] = $this->users->markClipped(Auth::User(), $related['objects']);
            }

            $expired = $this->locations->getEntities($location, $show_demo, 0, 3, true);
        }
        else if(Auth::check())
        {
            $entities['objects'] = $this->users->markClipped(Auth::User(), $entities['objects']);
        }

        // Tab Logic
        $default_tab = 'offers';
        $dealer_tab = 'lease_specials';
        if ($franchise->is_new_car_leads && !$entities['stats']['total'] && !$vehicles['objects'] && $new['stats']['total'])
        {
            $dealer_tab = 'new';
        }
        elseif (!$entities['stats']['total'] && $vehicles['stats']['total'] && $franchise->is_used_car_leads)
        {
            $dealer_tab = 'used';
        }
        if(!$franchise->is_dealer && $entities['stats']['total'] == 0)
        {
            $default_tab = 'about';
        }
        $vw->default_tab = $default_tab;
        $vw->dealer_tab = $dealer_tab;

        $vw->page_version = $merchant->page_version;

        $vw->entities = $entities;
        $vw->related = $related;
        $vw->expired = $expired;

        return $vw;
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

    protected function checkCategory($string)
    {
        $string = str_replace("_", "-", $string);
        $cat_found = false;
        $categories = SoeHelper::categories();
        foreach($categories as $id => $category_slug)
        {
            if($category_slug == $string)
                $cat_found = true;
        }

        return $cat_found;
    }

    protected function checkLowercase($type, $state , $city , $category , $subcategory , $merchant , $location)
    {
        if(strtolower($state) == 'in' && (strlen($city) == 2 || strlen($category) == 2))
            return false;

        if(ctype_upper($state))
        {
            $redirect = '/'.$type.'/'.strtolower($state);
            $redirect .= $city != '' ? '/'.SoeHelper::getSlug($city) : '';
            $redirect .= $category != '' ? '/'.$category : '';
            $redirect .= $subcategory != '' ? '/'.$subcategory : '';
            $redirect .= $merchant != '' ? '/'.$merchant : '';
            $redirect .= $location != '' ? '/'.$location : '';
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
        }

        return $vehicles;
    }
}
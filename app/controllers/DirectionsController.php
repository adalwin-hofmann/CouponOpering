<?php

class DirectionsController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AssetRepositoryInterface $assets,
        CategoryRepositoryInterface $categories,
        CityImageRepositoryInterface $cityImages,
        CompanyRepositoryInterface $companies,
        EntityRepositoryInterface $entities,
        FeatureRepositoryInterface $features,
        FranchiseRepositoryInterface $franchises,
        LocationRepositoryInterface $locations,
        MerchantRepositoryInterface $merchants,
        UserLocationRepositoryInterface $userLocations,
        UserRepositoryInterface $users,
        ZipcodeRepositoryInterface $zipcodes
    )
    {
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
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->userLocations = $userLocations;
        $this->users = $users;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->zipcodes = $zipcodes;
        parent::__construct();
    }

    /**
     * This is the base route for directions.
     */
    public function getIndex($merchant_slug, $two = null, $three = null, $four = null)
    {
        if($two || $three)
            return Redirect::to('/directions/'.$merchant_slug, 301);
        else
            return $this->directions($merchant_slug);

        /*if(!$two || !$three)
            return $this->oldDirections($merchant_slug, $two);
        else
            return $this->directions($merchant_slug, $two, $three, $four);*/
    }

    public function postIndex($merchant_slug)
    {
        return $this->directions($merchant_slug, Input::get('zipcode', null));
    }

    protected function directions($merchant_slug, $zipcode = null)
    {
        $merchant = $this->merchants->findBySlug($merchant_slug);
        if(!$merchant)
            return Redirect::to('/', 301);

        $code[] = View::make('home.jscode.directions');
        $vw = View::make('home.directions')->with('code', implode(' ', $code));
        $category = $this->categories->find($merchant->category_id);
        $subcategory = $this->categories->find($merchant->subcategory_id);
        $logo = $this->assets->getLogo($merchant);
        $latitude = $this->geoip->latitude;
        $longitude = $this->geoip->longitude;
        $vw->zipcode = null;
        if($zipcode)
        {
            $zipcode = $this->zipcodes->findByZipcode($zipcode);
            if($zipcode)
            {
                $latitude = $zipcode->latitude;
                $longitude = $zipcode->longitude;
                $vw->zipcode = $zipcode->zipcode;
            }
        }
        $locations = $this->locations->getMerchantLocationsByDistance(
            $merchant->id,
            $latitude,
            $longitude,
            Input::get('page', 0),
            5
        );
        //Redirect if only 1 location
        if ($locations['stats']['total'] == 1)
        {
            $location = $this->merchants->findNearestLocationBySlug($merchant_slug, $this->geoip->latitude, $this->geoip->longitude);
            if($location)
            {
                return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$category->slug.'/'.$subcategory->slug.'/'.$merchant_slug, 301);
            }
        }
        
        $locationsJson = $locations['objects']->toJson();
        $updated = $this->locations->getRecentlyUpdatedByMerchant($merchant->id, 0, 3);
        $startPagination = 0;
        $lastPage = floor($locations['stats']['total'] / $locations['stats']['take']);
        
        if($locations['stats']['total'] > $locations['stats']['returned'])
        {
            if ($locations['stats']['page'] == 0 || $locations['stats']['page'] == 1)
                $startPagination = 0;
            else
                $startPagination = $locations['stats']['page'] - 1;
        }
        $locationIds = array();
        foreach($locations['objects'] as $location)
        {
            $locationIds[] = $location->id;
        }

        $show_demo = Auth::check() ? $this->users->showDemo(Auth::user()) : false;
        $coupons = $this->merchants->getEntities($merchant->id, $show_demo, 0, 2, $locationIds);

        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make('Merchant', $merchant->id);
        
        if($person && $viewable)
            $viewable->view($person);

        $vw->merchant = $merchant;
        $vw->coupons = $coupons;
        $vw->startPagination = $startPagination;
        $vw->lastPage = $lastPage;
        $vw->locations = $locations;
        $vw->locationsJson = $locationsJson;
        $vw->updated = $updated;
        $vw->logo = $logo;
        $vw->images = $this->assets->getMerchantImages($merchant);
        $vw->videos = $this->assets->getMerchantVideos($merchant);
        $vw->pdfs = $this->assets->getMerchantPdfs($merchant);
        $vw->catName = $category->name;
        $vw->catSlug = $category->slug;
        $vw->subcatName = $subcategory->name;
        $vw->subcatSlug = $subcategory->slug;
        $vw->title = "Get Directions to ".$merchant->display." | ".$subcategory->name." Coupons, SaveOn";
        $vw->description = $merchant->meta_description == '' ? "Find directions to a ".$merchant->display." near you coupled with local ". $subcategory->name." coupons and discounts." : $merchant->meta_description;
        $vw->subheader = SoeHelper::cityStateReplace($merchant->sub_heading, $this->geoip);
        $vw->geoip = $this->geoip;

        return $vw;
    }

    protected function oldDirections($merchant_slug, $merchant_id)
    {
        $code = array();
        if($merchant_id)
            $merchant = $this->merchants->find($merchant_id);
        else
            $merchant = $this->merchants->findBySlug($merchant_slug);
            
        if(!$merchant)
            return Redirect::to('/');
        else
            return Redirect::to('/directions/'.$merchant_slug.'/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$merchant->id, 301);
    }

    protected function directionsOld($merchant_slug, $state, $city, $merchant_id = null)
    {
        $code = array();
        if($merchant_id)
            $merchant = $this->merchants->find($merchant_id);
        else
            $merchant = $this->merchants->findBySlug($merchant_slug);
            
        if(!$merchant)
            return Redirect::to('/');

        $lowerRedirect = $this->checkLowercase($merchant_slug, $state, $city, $merchant_id);
        if($lowerRedirect)
        {
            return $lowerRedirect;
        }

        if(Input::has('chg'))
        {
            $url = '/directions/'.$merchant_slug.'/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name);
            $url .= $merchant_id ? '/'.$merchant_id : '';
            $eid = Input::has('showeid') ? '?showeid='.Input::get('showeid') : '';
            return Redirect::to($url.$eid);
        }

        $zipcode = $this->zipcodes->getByCityState(SoeHelper::unSlug($city), $state);
        if(!$zipcode)
            App::abort(404);
        $this->userLocations->setLocation(Auth::person(), $zipcode->latitude, $zipcode->longitude, $city, $state);
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));

        $code[] = View::make('home.jscode.directions');
        $vw = View::make('home.directions')->with('code', implode(' ', $code));
        $vw->merchant = $merchant;

        $category = $this->categories->find($merchant->category_id);
        $subcategory = $this->categories->find($merchant->subcategory_id);

        $location = $this->merchants->findNearestLocationById($merchant->id, $this->geoip->latitude, $this->geoip->longitude);
        if(!$location)
            return Redirect::to('/coupons/'.strtolower($this->geoip->region_name).'/'.SoeHelper::getSlug($this->geoip->city_name).'/'.$category->slug.'/'.$subcategory->slug, 301);

        if(strtolower(SoeHelper::unSlug($city)) == 'mountain view' && strtoupper($state) == 'CA')
        {
            if(strtolower($location->city) != 'mountain view')
                return Redirect::to('/directions/'.$merchant->slug.'/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$merchant->id, 301);
        }
        $logo = $this->assets->getLogo($merchant);
        $vw->logo = $logo;
        $vw->catName = $category->name;
        $vw->catSlug = $category->slug;

        $vw->subcatName = $subcategory->name;
        $vw->subcatSlug = $subcategory->slug;
        //$vw->similar = Entity::getSimilar($merchant, Auth::check() ? Auth::User() : Auth::Nonmember());

        $vw->title = "Get Directions to ".$merchant->display." in ".$location->city.", ".$location->state." | ".$subcategory->name." Coupons, SaveOn";
        $vw->description = $merchant->meta_description == '' ? "Find directions to a ".$location->city.", ".$location->state." ".$merchant->display." near you coupled with local ". $subcategory->name." coupons and discounts." : $merchant->meta_description;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    protected function checkLowercase($merchant_slug, $state, $city, $merchant_id)
    {
        if(ctype_upper($state))
        {
            $redirect = '/directions/'.$merchant_slug.'/'.strtolower($state).'/'.$city;
            $redirect .= $merchant_id ? '/'.$merchant_id : '';
            return Redirect::to($redirect, 301);
        }

        return false;
    }

}
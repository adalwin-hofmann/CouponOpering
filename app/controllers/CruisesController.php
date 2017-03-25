<?php

class CruisesController extends BaseController {

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
        NonmemberRepositoryInterface $nonmembers,
        UserImpressionRepositoryInterface $userImpressions,
        UsedVehicleRepositoryInterface $usedVehicles,
        UserLocationRepositoryInterface $userLocations,
        UserRepositoryInterface $users,
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
        $this->nonmembers = $nonmembers;
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->userImpressions = $userImpressions;
        $this->usedVehicles = $usedVehicles;
        $this->userLocations = $userLocations;
        $this->users = $users;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->zipcodes = $zipcodes;
        parent::__construct();
    }

    public function getIndex()
	{
		$code = array();
		$code[] = View::make('sot.jscode.index');
		$vw = View::make('sot.cruises-index')->with('code', implode(' ', $code));
		$vw->title = "SaveOn Cruises";
        $vw->description = "SaveOn Cruises";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getSearch()
    {
        $code = array();
        $code[] = View::make('sot.jscode.index');
        $vw = View::make('sot.cruises-search')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Cruises - Search Results";
        $vw->description = "SaveOn Cruises - Search Results";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getReferral()
    {
        $code = array();
        $code[] = View::make('sot.jscode.index');
        $vw = View::make('sot.cruises-referral')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Cruises - Refer a Friend";
        $vw->description = "SaveOn Cruises - Refer a Friend";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getHowitworks()
    {
        $code = array();
        $code[] = View::make('sot.jscode.index');
        $vw = View::make('sot.cruises-howitworks')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Cruises - How It Works";
        $vw->description = "SaveOn Cruises - How It Works";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

}
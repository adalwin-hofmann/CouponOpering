<?php

class TravelController extends BaseController {

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
		$vw = View::make('sot.index')->with('code', implode(' ', $code));
		$vw->title = "SaveOn Travel";
        $vw->description = "SaveOn Travel";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getCity()
    {
        $code = array();
        $code[] = View::make('sot.jscode.index');
        $vw = View::make('sot.city')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Travel";
        $vw->description = "SaveOn Travel";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;

        return $vw;
    }

    public function getHotel()
    {
        $code = array();
        $code[] = View::make('sot.jscode.index');
        $vw = View::make('sot.hotel')->with('code', implode(' ', $code));
        $vw->title = "SaveOn Travel";
        $vw->description = "SaveOn Travel";
        $sohi = $this->features->findByName('home_improvement');
        $vw->sohi = empty($sohi) ? 0 : $sohi->value;
        $vw->generic_quote = false;
        $vw->quote_control = false;
        $vw->geoip = $this->geoip;
        return $vw;
    }

}
<?php namespace SOE\Clickables;

class BannerEntityClickable extends Clickable implements ClickableInterface
{
    public function __construct($object_id, $params = array())
    {
        parent::__construct();
        $this->model = $this->repository->find($object_id);
        $this->banners = \App::make('BannerRepositoryInterface');
        $this->categories = \App::make('CategoryRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->users = \App::make('UserRepositoryInterface');
        $this->geoip = json_decode(\GeoIp::getGeoIp('json'));
    }

    public function click(\SOE\Persons\PersonInterface $clicker)
    {
        if(!\SoeHelper::isBot() && $clicker->exists() && $clicker->shouldTrack())
        {
            $foreign = $clicker->getForeignKey();
            $banner = $this->banners->find($this->model->banner_id);
            $merchant = $this->merchants->find($banner->merchant_id);
            $location = $this->locations->find($this->model->location_id);
            $category = $this->categories->find($merchant->category_id);
            $subcategory = $this->categories->find($merchant->subcategory_id);
            $identity = $foreign == 'user_id' ? $this->users->find($clicker->getId())->email : 'non-'.$clicker->getId();
            $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
            $mp->identify($identity);
            $mp->track('Banner Click', array(
                '$city' => $this->geoip->city_name,
                'BannerId' => $banner->id,
                'BannerType' => $banner->type,
                'MerchantName' => $merchant->display,
                'MerchantId' => $banner->merchant_id,
                'Environment' => \App::environment(),
                'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                'LocationId' => $location->id,
                'FranchiseId' => $location->franchise_id,
                '$region' => $this->geoip->region_name,
                'Category' => !empty($category) ? $category->name : '',
                'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                'UserType' => ($foreign == 'user_id' ? 'User' : 'Nonmember')
            ));
        }
        return false;
    }
}
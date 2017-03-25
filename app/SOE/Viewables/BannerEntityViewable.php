<?php namespace SOE\Viewables;

class BannerEntityViewable extends Viewable implements ViewableInterface
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

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        if(!\SoeHelper::isBot() && $viewer->exists() && $viewer->shouldTrack())
        {
            $foreign = $viewer->getForeignKey();
            $banner = $this->banners->find($this->model->banner_id);
            $merchant = $this->merchants->find($banner->merchant_id);
            $location = $this->locations->find($this->model->location_id);
            $category = $this->categories->find($merchant->category_id);
            $subcategory = $this->categories->find($merchant->subcategory_id);
            $identity = $foreign == 'user_id' ? $this->users->find($viewer->getId())->email : 'non-'.$viewer->getId();
            $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
            $mp->identify($identity);
            $mp->track('Banner Impression', array(
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
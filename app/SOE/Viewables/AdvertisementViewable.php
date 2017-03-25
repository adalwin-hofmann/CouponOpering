<?php namespace SOE\Viewables;

class AdvertisementViewable extends Viewable implements ViewableInterface
{
    public function __construct($object_id, $params = array())
    {
        parent::__construct();
        $this->model = $this->repository->find($object_id);
        $this->adImpressions = \App::make('AdImpressionRepositoryInterface');
        $this->geoip = json_decode(\GeoIp::getGeoIp('json'));
    }

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        if(!\SoeHelper::isBot() && $viewer->exists() && $viewer->shouldTrack())
        {
            $foreign = $viewer->getForeignKey();
            $cartesian = \SoeHelper::getCartesian($this->geoip->latitude, $this->geoip->longitude);
            $data = array(
                'user_id' => 0,
                'nonmember_id' => 0,
                'advertisement_id' => $this->model->id,
                'city' => $this->geoip->city_name,
                'state' => $this->geoip->region_name,
                'latitude' => $this->geoip->latitude,
                'longitude' => $this->geoip->longitude,
                'latm' => $cartesian['latm'],
                'lngm' => $cartesian['lngm'],
                'url' => \URL::current()
            );
            $data[$foreign] = $viewer->getId();
            $view = $this->adImpressions->create($data);

            return $view;
        }
        return false;
    }
}
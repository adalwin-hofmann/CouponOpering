<?php namespace SOE\Viewables;

class VehicleEntityViewable extends Viewable implements ViewableInterface
{
    public function __construct($object_id, $params = array())
    {
        parent::__construct();
        if(isset($params[1]))
            $this->model = $this->repository->findByVendorInventoryId($params[1], $object_id);
        else
            $this->model = $this->repository->find($object_id);
        $this->userImpressions = \App::make('UserImpressionRepositoryInterface');
    }

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        $foreign = $viewer->getForeignKey();
        $data = array(
            'user_id' => 0,
            'nonmember_id' => 0,
            'location_id' => $this->model->location_id,
            'merchant_id' => $this->model->merchant_id,
            'vehicle_entity_id' => $this->model->id
        );
        
        if($viewer->exists() && $viewer->shouldTrack())
        {
            $data[$foreign] = $viewer->getId();
            $view = $this->userImpressions->create($data);
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            if(\App::environment() == 'prod') {
                $users = \App::make('UserRepositoryInterface');
                $nonmembers = \App::make('NonmemberRepositoryInterface');
                $vehicleEntities = \App::make('VehicleEntityRepositoryInterface');
                $locations = \App::make('LocationRepositoryInterface');
                $merchants = \App::make('MerchantRepositoryInterface');
                $categories = \App::make('CategoryRepositoryInterface');

                $person = $view->user_id != 0 ? $users->find($view->user_id) : $nonmembers->find($view->nonmember_id);
                $vehicle = $vehicleEntities->find($view->vehicle_entity_id);
                if(empty($person) || empty($vehicle))
                {
                    return;
                }
                
                $identity = $view->user_id != 0 ? $person->email : 'non-'.$view->nonmember_id;
                $location = $locations->find($vehicle->location_id);
                $merchant = $merchants->find($vehicle->merchant_id);
                $category = $categories->find($merchant->category_id);
                $subcategory = $categories->find($merchant->subcategory_id);
                $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                $mp->identify($identity);
                $mp->track('Vehicle Entity Impression', array(
                    '$city' => $geoip->city_name,
                    'VehicleEntityId' => $view->vehicle_entity_id,
                    'Environment' => \App::environment(),
                    'MerchantId' => $vehicle->merchant_id,
                    'MerchantName' => $merchant->display,
                    'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                    'LocationId' => $location->id,
                    'FranchiseId' => $location->franchise_id,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'UserType' => ($view->user_id != 0 ? 'User' : 'Nonmember')
                ));
            }
        }
        else
        {
            $view = $this->userImpressions->blank($data);
        }

        $view->vehicle = $this->model->toArray();
        $favoritable = new \SOE\Favoritables\VehicleEntityFavoritable($this->model->id);
        $favorite = $favoritable->isFavorite($viewer);
        $view->is_saved = $favorite ? 1 : 0;

        return $view;

        /*$type = $this->model->entitiable_type.'Viewable';
        $viewable = new $type($this->model->entitiable_id);
        $data['vehicle_entity_id'] = $this->model->id;
        return $viewable->view($person, $data);*/
    }
}
<?php namespace SOE\Viewables;

class WebsiteViewable extends Viewable implements ViewableInterface
{
    public function __construct($object_id, $params = array())
    {
        $repository = \App::make('LocationRepositoryInterface');
        parent::__construct($repository);
        $this->companies = \App::make('CompanyRepositoryInterface');
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->model = $this->repository->find($object_id);
        $this->userViews = \App::make('UserViewRepositoryInterface');
        $this->linkClicks = \App::make('UserLinkClickRepositoryInterface');
        $this->type = isset($params[1]) ? $params[1] : 'default';
    }

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        if(!$viewer->exists() || !$viewer->shouldTrack())
            return array();
        $foreign = $viewer->getForeignKey();
        $data = array(
            'user_id' => 0,
            'nonmember_id' => 0,
            'merchant_id' => $this->model->merchant_id,
            'franchise_id' => $this->model->franchise_id,
            'location_id' => $this->model->id,
            'url' => (strpos($this->model->website, 'http') === false) ? 'http://'.$this->model->website : $this->model->website,
            'type' => $this->type
        );
        $data[$foreign] = $viewer->getId();
        $view = $this->linkClicks->create($data);

        $location_id = $this->model->id;
        $type = $this->type;
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $viewer_id = $viewer->getId();
        if(true)//\App::environment() == 'prod')
        {
            \Queue::push(function($job) use ($viewer_id, $foreign, $location_id, $geoip, $type)
            {
                $linkClicks = \App::make('UserLinkClickRepositoryInterface');
                $nonmembers = \App::make('NonmemberRepositoryInterface');
                $users = \App::make('UserRepositoryInterface');
                $locations = \App::make('LocationRepositoryInterface');
                $merchants = \App::make('MerchantRepositoryInterface');
                $categories = \App::make('CategoryRepositoryInterface');
                $companies = \App::make('CompanyRepositoryInterface');
                $viewer = $foreign == 'user_id' ? $users->find($viewer_id) : $nonmembers->blank();
                if(\SoeHelper::isBot())
                {
                    $job->delete();
                    return;
                }
                if(!$viewer)
                {
                    $log = new \SOE\DB\SysLog;
                    $log->type = 'No Viewer Error';
                    $log->message = 'User Agent: '.(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
                    $log->save();
                    $job->delete();
                    return;
                }
                $location = $locations->find($location_id);
                $merchant = $merchants->find($location->merchant_id);
                $category = $categories->find($merchant->category_id);
                $subcategory = $categories->find($merchant->subcategory_id);
                $company = $companies->find($location->company_id);

                $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                $mp->identify($foreign == 'user_id' ? $viewer->email : 'non-'.$viewer_id);
                $mp->track('Merchant Website View', array(
                    '$city' => $geoip->city_name,
                    'Environment' => \App::environment(),
                    'FranchiseId' => $location->franchise_id,
                    'LocationId' => $location->id,
                    'MerchantId' => $location->merchant_id,
                    'MerchantName' => $location->merchant_name,
                    'MerchantNameAddress' => $location->merchant_name.' - '.$location->address,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => !empty($company) ? $company->id : '',
                    'CompanyName' => !empty($company) ? $company->name : '',
                    'UserType' => $foreign == 'user_id' ? 'User' : 'Nonmember',
                    'WebsiteType' => $type
                ));

                $job->delete();
            });
        }

        return $view;
    }
}
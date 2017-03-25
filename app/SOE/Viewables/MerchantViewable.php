<?php namespace SOE\Viewables;

class MerchantViewable extends Viewable implements ViewableInterface
{
    public function __construct($object_id, $params = array())
    {
        parent::__construct();
        $this->companies = \App::make('CompanyRepositoryInterface');
        $this->contests = \App::make('ContestRepositoryInterface');
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->model = $this->repository->find($object_id);
        $this->offers = \App::make('OfferRepositoryInterface');
        $this->reviews = \App::make('ReviewRepositoryInterface');
        $this->userViews = \App::make('UserViewRepositoryInterface');
    }

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        if(!$viewer->exists() || !$viewer->shouldTrack())
            return array();
        $foreign = $viewer->getForeignKey();
        $data = array(
            'user_id' => 0,
            'nonmember_id' => 0,
            'merchant_id' => $this->model->id,
            'franchise_id' => 0,
            'location_id' => 0,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'tracking_id' => \Cookie::get('tracking_id'),
            'url' => \Cookie::get('tracking_url'),
            'refer_id' => \Cookie::get('tracking_referid')
        );
        $data[$foreign] = $viewer->getId();
        $view = $this->userViews->create($data);

        $merchant_id = $this->model->id;
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $viewer_id = $viewer->getId();
        if(true)//\App::environment() == 'prod')
        {
            \Queue::push(function($job) use ($viewer_id, $foreign, $merchant_id, $geoip)
            {
                $userViews = \App::make('UserViewRepositoryInterface');
                $nonmembers = \App::make('NonmemberRepositoryInterface');
                $users = \App::make('UserRepositoryInterface');
                $merchants = \App::make('MerchantRepositoryInterface');
                $categories = \App::make('CategoryRepositoryInterface');
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
                $merchant = $merchants->find($merchant_id);
                $category = $categories->find($merchant->category_id);
                $subcategory = $categories->find($merchant->subcategory_id);

                $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                $mp->identify($foreign == 'user_id' ? $viewer->email : 'non-'.$viewer_id);
                $mp->track('Location View', array(
                    '$city' => $geoip->city_name,
                    'Environment' => \App::environment(),
                    'FranchiseId' => 0,//$location->franchise_id,
                    'LocationId' => 0,//$location->id,
                    'MerchantId' => $merchant->id,
                    'MerchantName' => $merchant->name,
                    'MerchantNameAddress' => $merchant->name.' - ',
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => '',
                    'CompanyName' => '',
                    'UserType' => $foreign == 'user_id' ? 'User' : 'Nonmember'
                ));

                $job->delete();
            });
        }

        return $view;
    }
}
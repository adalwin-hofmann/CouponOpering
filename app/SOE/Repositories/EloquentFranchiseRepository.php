<?php

class EloquentFranchiseRepository extends BaseEloquentRepository implements FranchiseRepository, ViewableInterface, RepositoryInterface
{
    protected $columns = array(
        'name',
        'company_id',
        'maghub_id',
        'merchant_id',
        'is_active',
        'max_prints',
        'mobile_redemption',
        'primary_contact',
        'is_demo',
        'deleted_at',
        'is_certified',
        'certified_at',
        'uncertified_at',
        'service_plan',
        'zipcode',
        'radius',
        'monthly_budget',
        'contact_phone',
        'sponsor_level',
        'sponsor_banner',
        'is_leads_confirmed',
        'banner_package',
    );

    protected $model = 'Franchise';

    /**
     * View this Franchise for a given person.
     *
     * @param PersonInterface $viewer
     */
    public function view(PersonInterface $viewer)
    {
        if($this->primary_key)
        {
            $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            $viewer_id = $viewer->id;
            $viewer_type = $viewer->getType();
            Queue::push(function($job) use ($viewer_id, $viewer_type, $user_agent)
            {
                $viewer = $viewer_type == 'User' ? User::find($viewer_id) : Nonmember::find($viewer_id);
                if(stristr($user_agent, 'bot') || stristr($user_agent, 'spider'))
                {
                    $job->delete();
                    return;
                }

                $view = UserView::blank();
                if($viewer->getType() == 'User')
                {
                    $view->user_id = $viewer->id;
                }
                else
                {
                    $view->nonmember_id = $viewer->id;
                }
                $view->merchant_id = $this->merchant_id;
                $view->franchise_id = $this->primary_key;
                $view->user_agent = $user_agent;
                $view->tracking_id = Cookie::get('tracking_id');
                $view->url = Cookie::get('tracking_url');
                $view->refer_id = Cookie::get('tracking_referid');
                $view->save();
                $job->delete();
            });
        }
    }

    /**
     * Get the locations belonging to this franchise.
     *
     * @return array LocationRepositories
     */
    public function locations()
    {
        if($this->primary_key)
        {
            $locations = SOE\DB\Location::where('franchise_id', '=', $this->primary_key);
            $stats = $this->getStats(clone $locations, 0, 0);
            $locations = $locations->get();
            $stats['stats']['returned'] = count($locations);
            $return = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = Location::blank();
                $loc = $loc->createFromModel($location);
                $return['objects'][] = $loc;
            }

            return array_merge($return, $stats);
        }
    }

    /**
     * Get the company that this franchise belongs to.
     *
     * @return CompanyRepository
     */
    public function company()
    {
        if($this->primary_key)
        {
            return Company::find($this->company_id);
        }
    }

    /**
     * Get a list of franchises matching a name query.
     *
     * @param string    $name
     * @param int       $page
     * @param int       $limit
     *
     * @return array Franchises
     */
    public function getByName($name, $page = 0, $limit = 0)
    {
        $name = str_replace("'", '', $name);
        $query = SOE\DB\Franchise::join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                                ->where('merchants.name', 'LIKE', '%'.$name.'%')
                                ->where('franchises.is_active', '=', '1')
                                ->orderBy('merchants.name', 'asc');
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $franchises = $query->get(array('franchises.*', 'merchants.display'));
        $return = array('objects' => array());
        foreach($franchises as $franchise)
        {
            $franch = Franchise::blank();
            $franch = $franch->createFromModel($franchise);
            $return['objects'][] = $franch;
        }

        return array_merge($return, $stats);
    }

    /***** API METHODS *****/

    /**
     * Get a list of franchises matching a name query.
     *
     * @return mixed Formatted array of franchises.
     */
    public function apiGetByName()
    {
        $name = Input::get('name', '');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getByName($name, $page, $limit));
    }
}
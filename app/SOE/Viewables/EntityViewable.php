<?php namespace SOE\Viewables;

class EntityViewable extends Viewable implements ViewableInterface
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
        $this->userImpressions = \App::make('UserImpressionRepositoryInterface');
    }

    public function view(\SOE\Persons\PersonInterface $viewer)
    {
        $foreign = $viewer->getForeignKey();
        $data = array(
            'user_id' => 0,
            'nonmember_id' => 0,
            'location_id' => $this->model->location_id,
            'merchant_id' => $this->model->merchant_id
        );

        if($this->model->entitiable_type == 'Contest')
        {
            $contest = $this->contests->find($this->model->entitiable_id);
            $view = $this->userImpressions->blank();
            $view->entity = $this->model->toArray();
            $view->contest = $contest->toArray();
            if($foreign == 'user_id')
                $application = $this->contests->isEntered($this->model->entitiable_id, $viewer->getId());
            else
                $application = null;
            $view->is_entered = $application ? 1 : 0;

            if($this->model->secondary_type == 'internal' || $this->model->secondary_type == 'external')
            {
                $randomnum = $this->contests->getSweepstakes($this->model->id);
                $view->randomnum = $randomnum;
            }
            return $view;
        }
        $offer = $this->offers->find($this->model->entitiable_id);
        $company = $this->companies->find($this->model->company_id);
        $franchise = $this->franchises->find($offer->franchise_id);
        $location = $this->locations->find($this->model->location_id);
        $merchant = $this->merchants->find($this->model->merchant_id);
        $data['offer_id'] = $this->model->entitiable_id;
        $data['entity_id'] = $this->model->id;
        $viewer_id = $viewer->getId();
        if($viewer->exists() && $viewer->shouldTrack() && !empty($viewer_id))
        {
            $data[$foreign] = $viewer->getId();
            $view = $this->userImpressions->create($data);
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            if(true){//\App::environment() == 'prod') {
                $users = \App::make('UserRepositoryInterface');
                $nonmembers = \App::make('NonmemberRepositoryInterface');
                $categories = \App::make('CategoryRepositoryInterface');
                $entities = \App::make('EntityRepositoryInterface');
                $person = $view->user_id != 0 ? $users->find($view->user_id) : $nonmembers->find($view->nonmember_id);
                $entity = $entities->find($view->entity_id);

                $identity = $view->user_id != 0 ? $person->email : 'non-'.$view->nonmember_id;
                $category = $categories->find($merchant->category_id);
                $subcategory = $categories->find($merchant->subcategory_id);
                $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                $mp->identify($identity);
                $mp->track('Offer Impression', array(
                    '$city' => $geoip->city_name,
                    'OfferId' => $view->offer_id,
                    'OfferName' => $entity->name,
                    'Environment' => \App::environment(),
                    'MerchantId' => $entity->merchant_id,
                    'MerchantName' => $merchant->display,
                    'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                    'LocationId' => $location->id,
                    'FranchiseId' => $location->franchise_id,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => !empty($company) ? $company->id : '',
                    'CompanyName' => !empty($company) ? $company->name : '',
                    'UserType' => ($view->user_id != 0 ? 'User' : 'Nonmember')
                ));
            }
        }
        else
        {
            if(empty($viewer_id))
            {
                $viewer_model = $viewer->getModel();
                \DB::table('sys_logs')->insert(array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'type' => 'No Viewer Error', 
                    'message' => $viewer_model->toJson()
                ));
            }
            $view = $this->userImpressions->blank($data);
        }

        $view->offer = $offer->toArray();
        $view->company_logo = (!empty($company))?$company->logo_image:'';
        $view->is_certified = $franchise->is_certified;
        $view->is_sohi_trial = $franchise->is_sohi_trial;
        if($foreign == 'user_id' && $viewer->exists())
        {
            $clip = $this->offers->isClipped($offer->id, $viewer->getId());
        }
        else
            $clip = null;

        $view->is_clipped = $clip ? 1 : 0;
        $view->can_print = $viewer->exists() ? $this->offers->canPrint($offer->id, $foreign, $viewer->getId()) : true;
        $reviews = $this->reviews->getOfferReviewVotes($offer->id);

        $up_count = 0;
        $down_count = 0;
        $my_review = 0;
        foreach($reviews as $review)
        {
            if($review->upvotes == 1)
            {
                $up_count++;
            }
            else
            {
                $down_count++;
            }
            $my_review = $viewer->exists() ? ($review->$foreign == $viewer->getId() ? $review->upvotes : $my_review) : $my_review;
        }
        $about = $location->about ? $location->about : $merchant->about;
        $truncated = \SoeHelper::truncate($about);
        $entity = $this->model->toArray();
        $entity['merchant_about_truncated'] = $truncated;
        $entity['merchant_about'] = $about;
        $view->entity = $entity;
        $view->down_count = $down_count;
        $view->up_count = $up_count;
        $view->my_review = $my_review;
        $view->offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
        return $view;
    }
}
<?php

class EloquentNonmemberRepository extends BaseEloquentRepository implements NonmemberRepository, PersonInterface, RepositoryInterface
{
    protected $columns = array(
        'rank_food_dining',
        'rank_home_services',
        'rank_health_beauty',
        'rank_auto_transportation',
        'rank_travel_entertainment',
        'rank_retail_fashion',
        'rank_special_services',
        'rank_community',
    );

    protected $model = 'Nonmember';

    /**
     * Retrieve an Object by Id.
     *
     * @param   int     $object_id
     * @param   boolean $with_trashed Include soft deleted results.
     * @return mixed
     */
    public function find($object_id = null, $with_trashed = false)
    {
        if($with_trashed)
            $object = \SOE\DB\Nonmember::on('mysql-write')->withTrashed()->find($object_id);
        else
            $object = \SOE\DB\Nonmember::on('mysql-write')->find($object_id);
        if(!empty($object))
        {
            $repo_model = $this->model;
            $repos = $repo_model::blank();
            $repos = $repos->createFromModel($object);
            $this->primary_key = $object->id;
            $this->attributes = $object->getAttributes();
            $this->original = $object->getAttributes();
            return $repos;
        }
        return;
    }

    /**
     * Retrieve a Nonmember's prints.
     *
     * @param  array  $filters
     * @param  int    $limit
     * @param  int    $page
     * @return mixed
     */
    public function getPrints(array $filters = array(), $limit = 0, $page = 0)
    {
        if($this->primary_key)
        {
            $filters[] = array('key' => 'nonmember_id', 'operator' => '=', 'value' => $this->primary_key);
        }

        $prints = UserPrint::get($filters, $limit, $page);
        $ids = array(0);
        foreach($prints['objects'] as $print)
        {
            $ids[] = $print->offer_id;
        }

        $offer_filters = array();
        $offer_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        $offers = Offer::get($offer_filters);
        $aOffers = array();
        foreach($offers['objects'] as $offer)
        {
            $aOffers[$offer->id] = $offer;
        }

        foreach($prints['objects'] as &$print)
        {
            $print->offer = $aOffers[$print->offer_id];
        }

        return $prints;
    }

    /**
     * Retrieve a User's views.
     *
     * @param  array  $filters
     * @param  int    $limit
     * @param  int    $page
     * @return mixed
     */
    public function getViews(array $filters = array(), $limit = 0, $page = 0)
    {
        if($this->primary_key)
        {
            $filters[] = array('key' => 'nonmember_id', 'operator' => '=', 'value' => $this->primary_key);
        }

        $views = UserView::get($filters, $limit, $page);
        $location_ids = array(0);
        $merchant_ids = array(0);
        $franchise_ids = array(0);
        foreach($views['objects'] as $view)
        {
            $location_ids[] = $view->location_id;
            $merchant_ids[] = $view->merchant_id;
            $franchise_ids[] = $view->franchise_id;
        }

        $location_filters = array();
        $location_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        $locations = Location::get($location_filters);
        $aLocations = array();
        foreach($locations['objects'] as $location)
        {
            $aLocations[$location->id] = $location;
        }

        $merchant_filters = array();
        $merchant_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        $merchants = Merchant::get($merchant_filters);
        $aMerchants = array();
        foreach($merchants['objects'] as $merchant)
        {
            $aMerchants[$merchant->id] = $merchant;
        }

        $franchise_filters = array();
        $franchise_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        $franchises = Franchise::get($franchise_filters);
        $aFranchises = array();
        foreach($franchises['objects'] as $franchise)
        {
            $aFranchises[$franchise->id] = $franchise;
        }

        foreach($views['objects'] as &$view)
        {
            $view->location = $aLocations[$view->location_id];
            $view->merchant = $aMerchants[$view->merchant];
            $view->franchise = $aFranchises[$view->franchise_id];
        }

        return $views;
    }

    /**
     * Retrieve a User's Redeems.
     *
     * @param  array  $filters
     * @param  int    $limit
     * @param  int    $page
     * @return mixed
     */
    public function getRedeems(array $filters = array(), $limit = 0, $page = 0)
    {
        if($this->primary_key)
        {
            $filters[] = array('key' => 'nonmember_id', 'operator' => '=', 'value' => $this->primary_key);
        }

        $redeems = UserRedeem::get($filters, $limit, $page);
        $ids = array(0);
        foreach($redeems['objects'] as $redeem)
        {
            $ids[] = $redeem->offer_id;
        }

        $redeem_filters = array();
        $redeem_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        $offers = Offer::get($redeem_filters);
        $aOffers = array();
        foreach($offers['objects'] as $offer)
        {
            $aOffers[$offer->id] = $offer;
        }

        foreach($redeems['objects'] as &$redeem)
        {
            $redeem->offer = $aOffers[$redeem->offer_id];
        }

        return $redeems;
    }

    /**
     * Print an Entity.
     *
     * @param EntityRepository $entity
     * @return $print
     */
    public function printEntity(EntityRepository $entity, $offer_rand = null)
    {
        if($offer_rand == null)
        {
            $offer_rand =  date('md').bin2hex(openssl_random_pseudo_bytes(2));
        }
        if($this->primary_key)
        {
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $print = UserPrint::blank();
            $print->code = $offer_rand;
            $print->nonmember_id = $this->primary_key;
            $print->offer_id = $offer->id;
            $print->entity_id = $entity->id;
            $print->location_id = $entity->location_id;
            $print->merchant_id = $entity->merchant_id;
            $print->tracking_id = Cookie::get('tracking_id');
            $print->url = Cookie::get('tracking_url');
            $print->refer_id = Cookie::get('tracking_referid');
            $print->save();
            $prints = SOE\DB\UserPrint::where('nonmember_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            if(\App::environment() == 'prod') {
                $location = Location::find($print->location_id);
                $merchant = Merchant::find($offer->merchant_id);
                $category = Category::find($merchant->category_id);
                $subcategory = Category::find($merchant->subcategory_id);
                $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                $mp->identify('non-'.$print->nonmember_id);
                $mp->track('Offer Print', array(
                    '$city' => $geoip->city_name,
                    'OfferId' => $offer->id,
                    'OfferName' => $offer->name,
                    'Environment' => App::environment(),
                    'MerchantId' => $offer->merchant_id,
                    'MerchantName' => $merchant->display,
                    'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                    'LocationId' => $location->id,
                    'FranchiseId' => $location->franchise_id,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => !empty($company) ? $company->id : '',
                    'CompanyName' => !empty($company) ? $company->name : '',
                    'UserType' => 'Nonmember'
                ));
            }

            $print->offer = $offer;
            $print->entity = $entity;
            $print->can_print = ($prints+1) < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::where('reviewable_id', '=', $offer->id)
                                    ->where('reviewable_type', '=', 'Offer')
                                    ->where('is_deleted', '=', '0')
                                    ->get(array('nonmember_id', 'upvotes'));
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
                $my_review = $review->nonmember_id == $this->primary_key ? $review->upvotes : $my_review;
            }
            $print->down_count = $down_count;
            $print->up_count = $up_count;
            $print->my_review = $my_review;
            return $print;
        }
    }

    /**
     * Redeem an Entity.
     *
     * @param EntityRepository $entity
     * @return $print
     */
    public function redeemEntity(EntityRepository $entity, $offer_rand = null)
    {
        if($offer_rand == null)
        {
            $offer_rand =  date('md').bin2hex(openssl_random_pseudo_bytes(2));
        }
        if($this->primary_key)
        {
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $print = UserPrint::blank();
            $print->code = $offer_rand;
            $print->nonmember_id = $this->primary_key;
            $print->offer_id = $offer->id;
            $print->entity_id = $entity->id;
            $print->location_id = $entity->location_id;
            $print->merchant_id = $entity->merchant_id;
            $print->is_redemption = true;
            $print->latitude = $geoip->latitude;
            $print->longitude = $geoip->longitude;
            $print->latm = $cartesian['latm'];
            $print->lngm = $cartesian['lngm'];
            $print->tracking_id = Cookie::get('tracking_id');
            $print->url = Cookie::get('tracking_url');
            $print->refer_id = Cookie::get('tracking_referid');
            $print->save();
            $prints = SOE\DB\UserPrint::where('nonmember_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();
            
            if(\App::environment() == 'prod') {
                $location = Location::find($print->location_id);
                $merchant = Merchant::find($offer->merchant_id);
                $category = Category::find($merchant->category_id);
                $subcategory = Category::find($merchant->subcategory_id);
                $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                $mp->identify('non-'.$print->nonmember_id);
                $mp->track('Offer Print', array(
                    '$city' => $geoip->city_name,
                    'OfferId' => $offer->id,
                    'OfferName' => $offer->name,
                    'Environment' => App::environment(),
                    'MerchantId' => $offer->merchant_id,
                    'MerchantName' => $merchant->display,
                    'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                    'LocationId' => $location->id,
                    'FranchiseId' => $location->franchise_id,
                    '$region' => $geoip->region_name,
                    'Category' => !empty($category) ? $category->name : '',
                    'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                    'CompanyID' => !empty($company) ? $company->id : '',
                    'CompanyName' => !empty($company) ? $company->name : '',
                    'UserType' => 'Nonmember'
                ));
            }

            $print->offer = $offer;
            $print->entity = $entity;
            $print->can_print = ($prints+1) < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::where('reviewable_id', '=', $offer->id)
                                    ->where('reviewable_type', '=', 'Offer')
                                    ->where('is_deleted', '=', '0')
                                    ->get(array('nonmember_id', 'upvotes'));
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
                $my_review = $review->nonmember_id == $this->primary_key ? $review->upvotes : $my_review;
            }
            $print->down_count = $down_count;
            $print->up_count = $up_count;
            $print->my_review = $my_review;
            return $print;
        }
    }

    /**
     * View an Entity.
     *
     * @param EntityRepository $entity
     * @return mixed $entity
     */
    public function viewEntity(EntityRepository $entity)
    {
        if($this->primary_key)
        {
            if($entity->entitiable_type == 'Contest')
            {
                $contest = Contest::find($entity->entitiable_id);
                $view = UserImpression::blank();
                $view->entity = $entity;
                $view->contest = $contest;
                $application = SOE\DB\ContestApplication::where('contest_id', '=', $entity->entitiable_id)->where('nonmember_id', '=', $this->primary_key)->first();
                $view->is_entered = empty($application) ? 0 : 1;
                return $view;
            }
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $franchise = Franchise::find($offer->franchise_id);
            $view = UserImpression::blank();
            $view->nonmember_id = $this->primary_key;
            $view->offer_id = $offer->id;
            $view->entity_id = $entity->id;
            $view->location_id = $entity->location_id;
            $view->merchant_id = $entity->merchant_id;
            $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            if(!stristr($user_agent, 'bot') && !stristr($user_agent, 'spider'))
            {
                $view->save();
            }
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $view_id = $view->id;
            if(\App::environment() == 'prod') {
                Queue::push(function($job) use ($view_id, $geoip)
                {
                    $view = UserImpression::find($view_id);
                    $offer = Offer::find($view->offer_id);
                    if(empty($offer))
                    {
                        $job->delete();
                        return;
                    }
                    $location = Location::find($view->location_id);
                    $merchant = Merchant::find($offer->merchant_id);
                    $category = Category::find($merchant->category_id);
                    $subcategory = Category::find($merchant->subcategory_id);
                    $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                    $mp->identify('non-'.$view->nonmember_id);
                    $mp->track('Offer Impression', array(
                        '$city' => $geoip->city_name,
                        'OfferId' => $offer->id,
                        'OfferName' => $offer->name,
                        'Environment' => App::environment(),
                        'MerchantId' => $offer->merchant_id,
                        'MerchantName' => $merchant->display,
                        'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                        'LocationId' => $location->id,
                        'FranchiseId' => $location->franchise_id,
                        '$region' => $geoip->region_name,
                        'Category' => !empty($category) ? $category->name : '',
                        'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                        'CompanyID' => !empty($company) ? $company->id : '',
                        'CompanyName' => !empty($company) ? $company->name : '',
                        'UserType' => 'Nonmember'
                    ));
                    $job->delete();
                });
            }

            $view->offer = $offer;
            $entity = SOE\DB\Entity::join('merchants', 'entities.merchant_id', '=', 'merchants.id')
                                    ->where('entities.id', '=', $entity->id)
                                    ->where('entities.entitiable_type', '=', 'Offer')
                                    ->remember(600)
                                    ->first(array('entities.*', DB::raw('merchants.about as merchant_about')));
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $view->entity = $ent;
            $view->company_logo = (!empty($company))?$company->logo_image:'';
            $view->is_certified = $franchise->is_certified;
            $view->is_sohi_trial = $franchise->is_sohi_trial;
            $view->is_clipped = 0;
            $prints = SOE\DB\UserPrint::where('nonmember_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();
            $view->can_print = $prints < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::where('reviewable_id', '=', $offer->id)
                                    ->where('reviewable_type', '=', 'Offer')
                                    ->where('is_deleted', '=', '0')
                                    ->get(array('user_id', 'upvotes'));
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
                $my_review = $review->nonmember_id == $this->primary_key ? $review->upvotes : $my_review;
            }
            $truncated = $this->truncate($view->entity->merchant_about);
            $view->entity->merchant_about_truncated = $truncated;
            $view->down_count = $down_count;
            $view->up_count = $up_count;
            $view->my_review = $my_review;
            return $view;
        }
    }

    /**
     * Review an reviewable item for this Nonmember.
     *
     * @param  ReviewableInterface $reviewable
     * @return Review
     */
    public function writeReview(ReviewableInterface $reviewable)
    {
        if($this->primary_key)
        {
            return $reviewable->writeReview($this);
        }
    }

    /**
     * Vote on a Review for this Nonmember.
     *
     * @param  ReviewRepository $review
     * @param  int              $vote 1 or -1
     * @return ReviewVote
     */
    public function voteOnReview(ReviewRepository $review, $vote)
    {
        if($this->primary_key)
        {
            return $review->vote($this, $vote);
        }
    }

    /**
     * Get an array of recommended Entities.
     *See getRecommendations in EloquentUserRepository
     * @return mixed Entities
     */
    public function getRecommendations($limit = 0, $geoip = array())
    {
        if($this->primary_key)
        {
            return Entity::getRecommendations($this, $geoip, $limit);
        }
    }

    /**
     * Click a banner.
     *
     * @param BannerRepository $offer
     * @return void
     */
    public function clickBanner(BannerRepository $banner)
    {
        if($this->primary_key)
        {
            $banner->click($this);
        }
    }

    /**
     * Fill out a contest application for this Nonmember.
     *
     * @param  ContestRepository $contest
     * @return mixed
     */
    public function applyForContest(ContestRepository $contest)
    {

        if($this->primary_key)
        {
            return ContestApplication::fillOut($contest, $this);
        }
    }

    /**
     * View a viewable item for this User.
     *
     * @param  ViewableInterface $viewable
     */
    public function view(ViewableInterface $viewable)
    {
        if($this->primary_key)
        {
            $viewer = $this->find($this->primary_key);
            return $viewable->view($viewer);
        }
    }

    public function setLocation($latitude = '', $longitude = '', $city = '', $state = '', $use_current = false)
    {
        if(!$this->primary_key)
        {
            return;
        }
        return UserLocation::setLocation($this, $latitude, $longitude, $city, $state, $use_current);
    }

    /**
     * Return the type of this Person.
     *
     * @return string Nonmember
     */
    public function getType()
    {
        return 'Nonmember';
    }

    /**
     * Determine if this user should see demo data.
     *
     * @return boolean
     */
    public function showDemo()
    {
        return false;
    }

    /**
     * Return the category rankings for this Person.
     *
     * @return array
     */
    public function getRankings()
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aRankings = array();
        foreach($cat_slugs as $slug)
        {
            $aRankings[$slug] = $this->{'rank_'.$slug};
        }
        return $aRankings;
    }
    /**
     * Return the category preferences for this Person.
     *
     * @return array
     */
    public function getPreferences()
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aPreferences = array();
        foreach($cat_slugs as $slug)
        {
            $aPreferences[$slug] = 1;
        }
        return $aPreferences;
    }

    /**
     * Suggest a merchant for this nonmember.
     *
     * @param array     $params Array of information about the merchant.
     * @return void
     */
    public function makeSuggestion(array $params = array())
    {
        if($this->primary_key)
        {
            $suggestion = new SOE\DB\Suggestion;
            foreach($params as $key => $value)
            {
                $suggestion->$key = $value;
            }
            $suggestion->nonmember_id = $this->primary_key;
            $suggestion->save();
        }
    }

    /***** API METHODS *****/

    /**
     * Retrieve an array of user prints given an array of filters and paging data.
     *
     * @api
     *
     * @return mixed Formatted array of printed offers.
     */
    public function apiGetPrints()
    {
        $filters = $this->getFilters();
        $limit = Input::get('limit', 0);
        $page = Input::get('page', 0);
        return $this->format($this->getPrints($filters, $limit, $page));
    }

    /**
     * Retrieve an array of user views given an array of filters and paging data.
     *
     * @api
     *
     * @return mixed Formatted array of viewed locations / merchants / franchises.
     */
    public function apiGetViews()
    {
        $filters = $this->getFilters();
        $limit = Input::get('limit', 0);
        $page = Input::get('page', 0);
        return $this->format($this->getViews($filters, $limit, $page));
    }

    /**
     * Retrieve an array of user redeems given an array of filters and paging data.
     *
     * @api
     *
     * @return mixed Formatted array of redeemed offers.
     */
    public function apiGetRedeems()
    {
        $filters = $this->getFilters();
        $limit = Input::get('limit', 0);
        $page = Input::get('page', 0);
        return $this->format($this->getRedeems($filters, $limit, $page));
    }

    /**
     * Print an Entity based on nonmember_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user print.
     */
    public function apiPrintEntity()
    {
        $nonmember_id = Input::get("nonmember_id");
        $entity_id = Input::get("entity_id");
        $offer_rand = Input::get("offer_rand", null);
        $this->primary_key = $nonmember_id;
        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            return $this->format($this->printEntity($entity, $offer_rand));
        }
    }

    /**
     * Redeem an Entity based on nonmember_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user print.
     */
    public function apiRedeemEntity()
    {
        $nonmember_id = Input::get("nonmember_id");
        $entity_id = Input::get("entity_id");
        $offer_rand = Input::get("offer_rand", null);
        $this->primary_key = $nonmember_id;
        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            return $this->format($this->redeemEntity($entity, $offer_rand));
        }
    }

    /**
     * View an Entity based on nonmember_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user view.
     */
    public function apiViewEntity()
    {
        $nonmember_id = Input::get("nonmember_id");
        $entity_id = Input::get("entity_id");
        $this->primary_key = $nonmember_id;
        if($type = Input::get('type'))
        {
            $model = studly_case($type);
            $entity = $model::find($entity_id);
        }
        else
            $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            //return $this->format($this->viewEntity($entity));
            return $this->format($this->view($entity));
        }
    }

    /**
     * Write a review based on nonmember_id, reviewable_type and reviewable_id.
     *
     * @api
     *
     * @return mixed Formatted review information.
     */
    public function apiWriteReview()
    {
        $nonmember_id = Input::get('nonmember_id');
        $this->find($nonmember_id);
        $type = studly_case(Input::get('reviewable_type'));
        $reviewable_id = Input::get('reviewable_id');
        $reviewable = $type::find($reviewable_id);
        return $this->format($this->writeReview($reviewable));
    }

    /**
     * Click a banner based on nonmember_id and banner_id.
     *
     * @api
     *
     * @return void
     */
    public function apiClickBanner()
    {
        $nonmember_id = Input::get('nonmember_id');
        $banner_id = Input::get('banner_id');
        $nonmember = $this->find($nonmember_id);
        $banner = Banner::find($banner_id);
        if($this->primary_key && !empty($banner))
        {
            return $this->format($this->clickBanner($banner));
        }
    }

    /**
     * Apply for a contest based on nonmember_id and contest_id.
     *
     * @api
     *
     * @return mixed Formatted contest application.
     */
    public function apiApplyForContest()
    {
        $nonmember_id = Input::get('nonmember_id');
        $this->find($nonmember_id);
        $contest_id = Input::get('contest_id');
        $contest = Contest::find($contest_id);
        if($this->primary_key && !empty($contest))
        {
            return $this->format($this->applyForContest($contest));
        }
    }

    /**
     * Set a nonmember location based on nonmember_id and latitude, longitude, city_name, region_name.
     *
     * @api
     *
     * @return mixed Formatted location information.
     */
    public function apiSetLocation()
    {
        $lat = Input::get('latitude', '');
        $lng = Input::get('longitude', '');
        $city = Input::get('city_name', '');
        $region = Input::get('region_name', '');
        $nonmember_id = Input::get('nonmember_id');
        $this->find($nonmember_id);
        if($this->primary_key)
        {
            return $this->format($this->setLocation($lat, $lng, $city, $region));
        }
    }

    /**
     * Get an array of recommended Entities based on nonmember_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetRecommendations()
    {
        $lat = Input::get('latitude', '');
        $lng = Input::get('longitude', '');

        if ($lat && $lng)
        {
            $zip = Zipcode::getClosest($lat, $lng);
            $geoip = new StdClass();
            $geoip->city_name = $zip->city;
            $geoip->region_name = $zip->state;
            $geoip->latitude = $lat;
            $geoip->longitude = $lng;
        }
        else
        {
            $geoip = array();
        }

        $nonmember_id = Input::get("nonmember_id");
        $limit = Input::get('limit', 0);
        $this->find($nonmember_id);
        return $this->format($this->getRecommendations($limit, $geoip));
    }

    /**
     * Vote on a review for this Nonmember based on nonmember_id, review_id, vote.
     *
     * @api
     *
     * @return mixed
     */
    public function apiVoteOnReview()
    {
        $nonmember_id = Input::get('nonmember_id');
        $review_id = Input::get('review_id');
        $vote = Input::get('vote');
        $this->find($nonmember_id);
        $review = Review::find($review_id);
        if(empty($review))
            return;
        return $this->format($this->voteOnReview($review, $vote));
    }

    /**
     * Suggest a merchant based on nonmember_id and merchant info.
     *
     * @api
     *
     * @return mixed
     */
    public function apiMakeSuggestion()
    {
        $params = Input::all();
        $nonmember_id = 0;
        if(isset($params['nonmember_id']))
        {
            $nonmember_id = $params['nonmember_id'];
            unset($params['nonmember_id']);
        }
        $nonmember = $this->find($nonmember_id);
        return $this->format($this->makeSuggestion($params));
    }
}

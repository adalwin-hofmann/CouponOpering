<?php

class EloquentUserRepository extends BaseEloquentRepository implements UserRepository, PersonInterface, RepositoryInterface
{
    protected $columns = array(
        'email',
        'password',
        'username',
        'type',
        'name',
        'address',
        'address2',
        'city',
        'state',
        'homezip',
        'workzip',
        'latitude',
        'longitude',
        'age',
        'birthday',
        'sex',
        'facebookid',
        'accesskey',
        'secretkey',
        'ip',
        'signup_source',
        'reputation',
        'badrep_date',
        'is_suspended',
        'win5kid',
        'rank_food_dining',
        'rank_home_services',
        'rank_health_beauty',
        'rank_auto_transportation',
        'rank_travel_entertainment',
        'rank_retail_fashion',
        'rank_special_services',
        'food_dining_preference',
        'home_services_preference',
        'health_beauty_preference',
        'auto_transportation_preference',
        'travel_entertainment_preference',
        'retail_fashion_preference',
        'special_services_preference',
        'is_deleted',
        'password_reset_notification',
        'contest_end_notification',
        'daily_deal_end_notification',
        'coupon_end_notification',
        'unredeemed_notification',
        'new_offers_notification',
        'love_offers_notification',
        'rank_community',
        'community_preference',
        'signup_reference',
    );

    protected $model = 'User';

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
            $object = \SOE\DB\User::on('mysql-write')->withTrashed()->find($object_id);
        else
            $object = \SOE\DB\User::on('mysql-write')->find($object_id);
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
     * Retrieve a User by email address.
     *
     * @param  string  $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        $filters = array();
        $filters[] = array('key' => 'email', 'operator' => '=', 'value' => $email);
        $user = User::get($filters, 1);
        return empty($user['objects']) ? array() : $user['objects'][0];
    }

    /**
     * Determine if a given type matches one of the User's type.
     *
     * @param string $type
     * @return boolean
     */
    public function checkType($type_string)
    {
        $types = explode(',', $this->type);
        $found = false;
        foreach($types as $type)
        {
            if(strtolower(trim($type)) == $type_string)
            {
                $found = true;
            }
        }
        return $found;
    }

    /**
     * Retrieve a User's prints.
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
            $filters[] = array('key' => 'user_id', 'operator' => '=', 'value' => $this->primary_key);
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
            $filters[] = array('key' => 'user_id', 'operator' => '=', 'value' => $this->primary_key);
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
        $location_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $location_ids);
        $locations = Location::get($location_filters);
        $aLocations = array();
        foreach($locations['objects'] as $location)
        {
            $aLocations[$location->id] = $location;
        }

        $merchant_filters = array();
        $merchant_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $merchant_ids);
        $merchants = Merchant::get($merchant_filters);
        $aMerchants = array();
        foreach($merchants['objects'] as $merchant)
        {
            $aMerchants[$merchant->id] = $merchant;
        }

        $franchise_filters = array();
        $franchise_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $franchise_ids);
        $franchises = Franchise::get($franchise_filters);
        $aFranchises = array();
        foreach($franchises['objects'] as $franchise)
        {
            $aFranchises[$franchise->id] = $franchise;
        }

        foreach($views['objects'] as &$view)
        {
            $view->location = empty($aLocations) ? array() : $aLocations[$view->location_id];
            $view->merchant = empty($aMerchants) ? array() : $aMerchants[$view->merchant_id];
            $view->franchise = empty($aFranchises) ? array() : $aFranchises[$view->franchise_id];
        }

        return $views;
    }

    /**
     * Retrieve a User's Clips.
     *
     * @param  array  $filters
     * @param  int    $limit
     * @param  int    $page
     * @return mixed
     */
    public function getClips($is_dailydeal, $limit = 0, $page = 0)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\UserClipped::on('mysql-write')
                                        ->where('user_id', '=', $this->primary_key)
                                        ->where('is_deleted', '=', '0')
                                        ->orderBy('created_at', 'desc');
            //$stats = $this->getStats(clone $query, $limit, $page);
            $clips = $query->get();
            $aClipIDs = array(0);
            foreach($clips as $clip)
            {
                $aClipIDs[] = $clip->offer_id;
            }
            $query = SOE\DB\Entity::join('user_clipped', function($join)
                                    {
                                        $join->on('entities.entitiable_id', '=', 'user_clipped.offer_id');
                                        $join->on('user_clipped.is_deleted', '=', DB::raw('0'));
                                    })   
                                    ->whereIn('entities.entitiable_id', $aClipIDs)
                                    ->where('entities.entitiable_type', '=', 'Offer')
                                    ->where('entities.is_dailydeal', '=', $is_dailydeal)
                                    ->where(function($query)
                                    {
                                        $query->where('expires_year', '>', date('Y'));
                                        $query->orWhere(function($query)
                                        {
                                            $query->where('expires_day', '>=', date('z'));
                                            $query->where('expires_year', date('Y'));
                                        });
                                    })
                                    ->orderBy('user_clipped.created_at', 'desc')
                                    ->groupBy('entities.entitiable_id');
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $stats = $this->getStats(clone $query, $limit, $page, true);
            $entities = $query->remember(600)->get(array('entities.*'));
            $stats['stats']['returned'] = count($entities);
            $return = array('objects' => array());
            foreach($entities as $entity)
            {
                $ent = Entity::blank();
                $ent = $ent->createFromModel($entity);
                $ent->is_clipped = 1;
                $return['objects'][] = $ent;
            }

            return array_merge($return, $stats);
        }
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
            $filters[] = array('key' => 'user_id', 'operator' => '=', 'value' => $this->primary_key);
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
     * @return mixed $print
     */
    public function printEntity(EntityRepository $entity, $offer_rand = null)
    {
        if($offer_rand == null)
        {
            $offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
        }
        if($this->primary_key)
        {
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $print = UserPrint::blank();
            $print->code = $offer_rand;
            $print->user_id = $this->primary_key;
            $print->offer_id = $offer->id;
            $print->entity_id = $entity->id;
            $print->location_id = $entity->location_id;
            $print->merchant_id = $entity->location_id;
            $print->tracking_id = Cookie::get('tracking_id');
            $print->url = Cookie::get('tracking_url');
            $print->refer_id = Cookie::get('tracking_referid');
            if($entity->print_override != '')
            {
                $print->type = 'referral';
            }
            $print->save();
            $prints = SOE\DB\UserPrint::where('user_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();

            $geoip = json_decode(GeoIp::getGeoIp('json'));
            if(\App::environment() == 'prod') {
                $user = User::find($print->user_id);
                $location = Location::find($print->location_id);
                $merchant = Merchant::find($offer->merchant_id);
                $category = Category::find($merchant->category_id);
                $subcategory = Category::find($merchant->subcategory_id);
                $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                $mp->identify($user->email);
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
                    'UserType' => 'User'
                ));
            }

            $print->offer = $offer;
            $print->entity = $entity;
            $clip = SOE\DB\UserClipped::on('mysql-write')
                                    ->where('user_id', '=', $this->primary_key)
                                    ->where('offer_id', '=', $offer->id)
                                    ->where('is_deleted', '=', '0')
                                    ->first();
            $print->is_clipped = !empty($clip);
            $print->can_print = ($prints+1) < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::on('mysql-write')
                                    ->where('reviewable_id', '=', $offer->id)
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
                $my_review = $review->user_id == $this->primary_key ? $review->upvotes : $my_review;
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
     * @return mixed $print
     */
    public function redeemEntity(EntityRepository $entity, $offer_rand = null)
    {
        if($offer_rand == null)
        {
            $offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
        }
        if($this->primary_key)
        {
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $print = UserPrint::blank();
            $print->code = $offer_rand;
            $print->user_id = $this->primary_key;
            $print->offer_id = $offer->id;
            $print->entity_id = $entity->id;
            $print->location_id = $entity->location_id;
            $print->merchant_id = $entity->location_id;
            $print->is_dedemption = true;
            $print->latitude = $geoip->latitude;
            $print->longitude = $geoip->longitude;
            $print->latm = $cartesian['latm'];
            $print->lngm = $cartesian['lngm'];
            $print->tracking_id = Cookie::get('tracking_id');
            $print->url = Cookie::get('tracking_url');
            $print->refer_id = Cookie::get('tracking_referid');

            if($entity->print_override != '')
            {
                $print->type = 'referral';
            }
            $print->save();
            $prints = SOE\DB\UserPrint::where('user_id', '=', $this->primary_key)
                                        ->where('offer_id', '=', $offer->id)
                                        ->count();
            if(\App::environment() == 'prod') {
                $user = User::find($print->user_id);
                $location = Location::find($print->location_id);
                $merchant = Merchant::find($offer->merchant_id);
                $category = Category::find($merchant->category_id);
                $subcategory = Category::find($merchant->subcategory_id);
                $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                $mp->identify($user->email);
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
                    'UserType' => 'User'
                ));
            }

            $print->offer = $offer;
            $print->entity = $entity;
            $clip = SOE\DB\UserClipped::on('mysql-write')
                                    ->where('user_id', '=', $this->primary_key)
                                    ->where('offer_id', '=', $offer->id)
                                    ->where('is_deleted', '=', '0')
                                    ->first();
            $print->is_clipped = !empty($clip);
            $print->can_print = ($prints+1) < $offer->max_prints ? 1 : 0;
            $reviews = SOE\DB\Review::on('mysql-write')
                                    ->where('reviewable_id', '=', $offer->id)
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
                $my_review = $review->user_id == $this->primary_key ? $review->upvotes : $my_review;
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
                $application = SOE\DB\ContestApplication::where('contest_id', '=', $entity->entitiable_id)->where('user_id', '=', $this->primary_key)->first();
                $view->is_entered = empty($application) ? 0 : 1;
                if($entity->secondary_type == 'internal' || $entity->secondary_type == 'external')
                {
                    $randomnum = Contest::getSweepstakes($entity);
                    $view->randomnum = $randomnum;
                }
                return $view;
            }
            $offer = Offer::find($entity->entitiable_id);
            $company = SOE\DB\Company::where('id','=',$entity->company_id)->first();
            $franchise = Franchise::find($offer->franchise_id);
            $location = Location::find($entity->location_id);
            $view = UserImpression::blank();
            $view->user_id = $this->primary_key;
            $view->entity_id = $entity->id;
            $view->offer_id = $offer->id;
            $view->location_id = $entity->location_id;
            $view->merchant_id = $entity->merchant_id;
            $view->save();
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $view_id = $view->id;
            if(\App::environment() == 'prod') {
                Queue::push(function($job) use ($view_id, $geoip)
                {
                    $view = UserImpression::find($view_id);
                    $user = User::find($view->user_id);
                    $entity = Entity::find($view->entity_id);
                    if(empty($user) || empty($entity))
                    {
                        $job->delete();
                        return;
                    }
                    $location = Location::find($entity->location_id);
                    $merchant = Merchant::find($entity->merchant_id);
                    $category = Category::find($merchant->category_id);
                    $subcategory = Category::find($merchant->subcategory_id);
                    $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                    $mp->identify($user->email);
                    $mp->track('Offer Impression', array(
                        '$city' => $geoip->city_name,
                        'OfferId' => $view->offer_id,
                        'OfferName' => $entity->name,
                        'Environment' => App::environment(),
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
                        'UserType' => 'User'
                    ));
                    $job->delete();
                });
            }

            $view->offer = $offer;
            $entity = SOE\DB\Entity::join('merchants', 'entities.merchant_id', '=', 'merchants.id')
                                    ->where('entities.id', '=', $entity->id)
                                    ->where('entities.entitiable_type', '=', 'Offer')
                                    ->first(array('entities.*', DB::raw('merchants.about as merchant_about')));
            $ent = Entity::blank();
            $ent = $ent->createFromModel($entity);
            $view->entity = $ent;
            $view->company_logo = (!empty($company))?$company->logo_image:'';
            $view->is_certified = $franchise->is_certified;
            $view->is_sohi_trial = $franchise->is_sohi_trial;
            $clip = SOE\DB\UserClipped::where('user_id', '=', $this->primary_key)
                                    ->where('offer_id', '=', $offer->id)
                                    ->where('is_deleted', '=', '0')
                                    ->first();
            $view->is_clipped = !empty($clip);
            $prints = SOE\DB\UserPrint::where('user_id', '=', $this->primary_key)
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
                $my_review = $review->user_id == $this->primary_key ? $review->upvotes : $my_review;
            }
            $about = $location->about ? $location->about : $view->entity->merchant_about;
            $truncated = $this->truncate($about);
            $view->entity->merchant_about_truncated = $truncated;
            $view->entity->merchant_about = $about;
            $view->down_count = $down_count;
            $view->up_count = $up_count;
            $view->my_review = $my_review;
            $view->offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
            return $view;
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

    public function setRecovery($email, $uniq)
    {
        $user = User::findByEmail($email);
        $timer = Feature::findByName('password_recovery_timer');
        $timer = empty($timer) ? 60 : $timer->value;
        if(!empty($user))
        {
            DB::table('recovery')->insert(array(
                'created_at' => DB::raw('NOW()'),
                'updated_at' => DB::raw('NOW()'),
                'user_id' => $user->id,
                'email' => $email,
                'recovery_id' => $uniq,
                'type' => 'password',
                'expires_at' => DB::raw("DATE_ADD(NOW(), INTERVAL ".$timer." MINUTE")
            ));
        }
    }

    public function validateRecovery($email, $uniq)
    {
        $valid = DB::table('recovery')->where('email', '=', $email)
                                    ->where('recovery_id', '=', $uniq)
                                    ->where('type', '=', 'password')
                                    ->where('expires_at', '>', DB::raw("NOW()"))
                                    ->first();
        if(!empty($valid))
        {
            DB::table('recovery')->where('email', '=', $email)
                                ->where('recovery_id', '=', $uniq)
                                ->where('type', '=', 'password')
                                ->delete();
        }

        return !empty($valid);
    }

    /**
     * Clip an Offer.
     *
     * @param OfferRepository $offer
     * @return mixed $clip
     */
    public function clipOffer(OfferRepository $offer)
    {
        if($this->primary_key)
        {
            $clip = UserClipped::blank();
            $clip->user_id = $this->primary_key;
            $clip->offer_id = $offer->id;
            $clip->save();

            return $clip;
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
     * Fill out a contest application for this User.
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
     * Review an reviewable item for this User.
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
     * Vote on a review for this User.
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
     * Share an shareable item for this User.
     *
     * @param  ShareableInterface $shareable
     * @return Share
     */
    public function share(ShareableInterface $shareable, $type, $params = array())
    {
        if($this->primary_key)
        {
            return $shareable->share($this, $type, $params);
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

    /**
     * Return the type of this Person.
     *
     * @return string User
     */
    public function getType()
    {
        return 'User';
    }

    /**
     * Determine if this user should see demo data.
     *
     * @return boolean
     */
    public function showDemo()
    {
        if($this->primary_key)
        {
            $types = explode(',', $this->type);
            if((in_array('Employee', $types) || in_array('Demo', $types)))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the category rankings for this Person.
     *
     * @return array
     */
    public function getRankings()
    {
        if($this->primary_key)
        {
            $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
            $aRankings = array();
            foreach($cat_slugs as $slug)
            {
                $aRankings[$slug] = $this->{'rank_'.$slug};
            }
            return $aRankings;
        }
    }

    /**
     * Return the category preferences for this Person.
     *
     * @return array
     */
    public function getPreferences()
    {
        if($this->primary_key)
        {
            $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
            $aPreferences = array();
            foreach($cat_slugs as $slug)
            {
                $aPreferences[$slug] = $this->{$slug.'_preference'};
            }
            return $aPreferences;
        }
    }

    /**
     * Add a Role to this User.
     *
     * @param  RoleRepository $role
     * @return void
     */
    public function attachRole(RoleRepository $role)
    {
        if($this->primary_key)
        {
            $existing = DB::table('role_users')->where('role_id', '=', $role->id)->where('user_id', '=', $this->primary_key)->first();
            if(empty($existing))
            {
                DB::table('role_users')->insert(array('user_id' => $this->primary_key, 'role_id' => $role->id));
            }
        }
    }

    /**
     * Remove a Role from this User.
     *
     * @param  RoleRepository $role
     * @return void
     */
    public function removeRole(RoleRepository $role)
    {
        DB::table('role_users')->where('role_id', '=', $role->id)->where('user_id', '=', $this->primary_key)->delete();
    }

    /**
     * Get Roles for this User.
     *
     * @param  array $filters
     * @param  int   $limit
     * @param  int   $page
     * @return array
     */
    public function getRoles(array $filters = array(), $limit = 0, $page = 0)
    {
        $query = DB::table('role_users')->where('user_id', '=', $this->primary_key);
        $query = $this->parseFilters($query, $filters);
        $query = $this->paginator($query, $limit, $page);
        $role_users = $query->get();

        $ids = array();
        foreach($role_users as $ru)
        {
            $ids[] = $ru->role_id;
        }

        $role_filters = array();
        $role_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        return Role::get($role_filters);
    }

    /**
     * Get the Rules belonging to this User.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = SOE\DB\User::where('users.id', '=', $this->primary_key)
                                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                                        ->join('role_rules', 'roles.id', '=', 'role_rules.role_id')
                                        ->join('rules', 'role_rules.rule_id', '=', 'rules.id')
                                        ->groupBy('rules.id')
                                        ->get(array('rules.*'));
        $return = array();
        foreach($rules as $r)
        {
            $rule = Rule::blank();
            foreach($r->getAttributes as $key => $value)
            {
                $rule->$key = $value;
            }
            $rule->primary_key = $r->id;
            $rule->sync();
            $return[] = $rule;
        }
        return $return;
    }

    public function getEnteredContests()
    {
        if($this->primary_key)
        {
            $contests = SOE\DB\Contest::join('contest_applications', 'contest_applications.contest_id', '=', 'contests.id')
                                                ->where('contest_applications.user_id', '=', $this->primary_key)
                                                ->where('contests.expires_at', '>', DB::raw('NOW()'))
                                                ->groupBy('contest_applications.contest_id')
                                                ->get(array('contests.*'));
            $return = array('objects' => array(), 'stats' => array());
            foreach($contests as $contest)
            {
                $ent = Contest::blank();
                $ent = $ent->createFromModel($contest);
                $return['objects'][] = $ent;
            }
            $return['stats']['total'] = count($contests);
            $return['stats']['take'] = count($contests);
            $return['stats']['page'] = 0;
            $return['stats']['returned'] = count($contests);
            return $return;
        }
    }

    public function getExpiredEnteredContests()
    {
        if($this->primary_key)
        {
            $contests = SOE\DB\Contest::join('contest_applications', 'contest_applications.contest_id', '=', 'contests.id')
                                                ->where('contest_applications.user_id', '=', $this->primary_key)
                                                ->where('contests.expires_at', '<', DB::raw('NOW()'))
                                                ->groupBy('contest_applications.contest_id')
                                                ->get(array('contests.*'));
            $return = array('objects' => array(), 'stats' => array());
            foreach($contests as $contest)
            {
                $ent = Contest::blank();
                $ent = $ent->createFromModel($contest);
                $return['objects'][] = $ent;
            }
            $return['stats']['total'] = count($contests);
            $return['stats']['take'] = count($contests);
            $return['stats']['page'] = 0;
            $return['stats']['returned'] = count($contests);
            return $return;
        }
    }

    public function getNearbyContests($page = 0, $limit = 12)
    {
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $entries = SOE\DB\ContestApplication::where('user_id', '=', $this->primary_key)->get(array('contest_id'));
        $aEntryIds = array(0);
        foreach($entries as $entry)
        {
            $aEntryIds[] = $entry->contest_id;
        }
        $aStates = Zipcode::getSurroundingStates($geoip->latitude, $geoip->longitude);
        $query = SOE\DB\Entity::whereIn('state', $aStates)
                                ->whereNotIn('entitiable_id', $aEntryIds)
                                ->where(function($query)
                                {
                                    $query->where('starts_year', '=', date('Y'));
                                    $query->where('starts_day', '<=', (date('z')+1));
                                    $query->orWhere('starts_year', '<', (date('Y')));
                                })
                                ->where(function($query)
                                {
                                    $query->where('expires_year', '=', date('Y'));
                                    $query->where('expires_day', '>=', (date('z')+1));
                                    $query->orWhere('expires_year', '>=', (date('Y')+1));
                                })
                                ->where('entitiable_type', '=', 'Contest');
        if($limit)
        {
            $query = $query->skip($page*$limit)->take($limit);
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        $contests = $query->orderBy('distance', 'asc')
                            ->get(array('entities.*', DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $stats['stats']['returned'] = count($contests);
        $return = array('objects' => array());
        foreach($contests as $contest)
        {
            $ent = Entity::blank();
            $ent = $ent->createFromModel($contest);
            $return['objects'][] = $ent;
        }
        return array_merge($return, $stats);
    }

    /**
     * Suggest a merchant for this user.
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
            $suggestion->user_id = $this->primary_key;
            $suggestion->save();
        }
    }

    /**
     * Set this User's location.
     *
     * @param float     $latitude   Default 0
     * @param float     $longitude  Default 0
     * @param string    $city       Default ''
     * @param state     $state      Default ''
     * @param boolean   $use_current Default false
     * @return mixed $location
     */
    public function setLocation($latitude = '', $longitude = '', $city = '', $state = '', $use_current = false)
    {
        if(!$this->primary_key)
        {
            return;
        }
        return UserLocation::setLocation($this, $latitude, $longitude, $city, $state, $use_current);
    }

    /**
     * Save a favorite location for this user.
     *
     * @return mixed $location
     */
    public function saveLocation()
    {
        if($this->primary_key)
        {
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $location = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                            ->where('is_deleted', '=', '0')
                            ->where('city', '=', $geoip->city_name)
                            ->where('state', '=', $geoip->region_name)
                            ->first();
            if(empty($location))
            {
                $location = UserLocation::blank();
                $location->user_id = $this->primary_key;
                $location->city = $geoip->city_name;
                $location->state = $geoip->region_name;
                $location->latitude = $geoip->latitude;
                $location->longitude = $geoip->longitude;
                $location->save();
            }

            $query = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                                        ->where('is_deleted', '=', '0')
                                        ->orderBy('created_at', 'desc');
            $stats = $this->getStats(clone $query, 5, 0);
            $locations = $query->take(5)
                                ->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = UserLocation::blank();
                $loc = $loc->createFromModel($location);
                $loc->my_point = '';
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Create a saved location for a user based on location data.
     *
     * @param double    $latitude
     * @param double    $longitude
     * @param string    $city
     * @param string    $state
     * @param int       $page Default 0.
     * @param int       $limit Default 5.
     */
    public function saveSearchLocation($latitude, $longitude, $city, $state, $page = 0, $limit = 5)
    {
        if($this->primary_key)
        {
            $location = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                            ->where('is_deleted', '=', '0')
                            ->where('city', '=', $city)
                            ->where('state', '=', $state)
                            ->first();
            if(empty($location))
            {
                $location = UserLocation::blank();
                $location->user_id = $this->primary_key;
                $location->city = $city;
                $location->state = $state;
                $location->latitude = $latitude;
                $location->longitude = $longitude;
                $location->save();
            }

            $query = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                                        ->where('is_deleted', '=', '0')
                                        ->orderBy('created_at', 'desc');
            $stats = $this->getStats(clone $query, $limit, $page);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $locations = $query->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = UserLocation::blank();
                $loc = $loc->createFromModel($location);
                $loc->my_point = '';
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Delete a user's saved location.
     *
     * @param int   $location_id
     * @return mixed $locations
     */
    public function deleteSavedLocation($location_id)
    {
        if($this->primary_key)
        {
            $location = SOE\DB\UserLocation::find($location_id);
            if(!empty($location) && $location->user_id == $this->primary_key)
            {
                $location->is_deleted = 1;
                $location->save();
            }

            $query = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                                        ->where('is_deleted', '=', '0')
                                        ->orderBy('created_at', 'desc');
            $stats = $this->getStats(clone $query, 5, 0);
            $locations = $query->take(5)
                                ->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = UserLocation::blank();
                $loc = $loc->createFromModel($location);
                $loc->my_point = '';
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
        }
    }

    /**
     * Retrieve the user's saved locations.
     *
     * @param int   $page  Default 0
     * @param int   $limit Default 5
     * @return mixed $locations
     */
    public function getSavedLocations($page = 0, $limit = 5)
    {
        $query = SOE\DB\UserLocation::where('user_id', '=', $this->primary_key)
                                        ->where('is_deleted', '=', '0')
                                        ->orderBy('created_at', 'desc');
            $stats = $this->getStats(clone $query, $limit, $page);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $locations = $query->get();
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $loc = UserLocation::blank();
                $loc = $loc->createFromModel($location);
                $loc->my_point = '';
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
    }

    /**
     * Favorite the given Favoritable for this user.
     *
     * @param FavoritableInterface $favoritable
     * @return mixed
     */
    public function favorite(FavoritableInterface $favoritable)
    {
        if($this->primary_key)
        {
            return $favoritable->favorite($this);
        }
    }

    /**
     * Get the favorited locations for this user.
     *
     * @param int       $page Default 0.
     * @param int       $limit Default 12.
     * @return mixed
     */
    public function getFavoriteLocations($page = 0, $limit = 12)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                        ->join(DB::raw('categories cat'), 'merchants.category_id', '=', 'cat.id')
                                        ->join(DB::raw('categories subcat'), 'merchants.subcategory_id', '=', 'subcat.id')
                                        ->join('user_favorites', 'user_favorites.favoritable_id', '=', 'locations.id')
                                        ->where('user_favorites.favoritable_type', '=', 'SOE\\DB\\Location')
                                        ->where('user_favorites.is_deleted', '=', '0')
                                        ->where('user_id', '=', $this->primary_key)
                                        ->orderBy('locations.name');
            $stats = $this->getStats(clone $query, $limit, $page);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $locations = $query->get(array('locations.*', DB::raw('cat.slug as category_slug'), DB::raw('subcat.slug as subcategory_slug')));
            $stats['stats']['returned'] = count($locations);
            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $entities = SOE\DB\Entity::where('location_id', '=', $location->id)
                                            ->where('state', '=', $location->state)
                                            ->where(function($query)
                                            {
                                                $query->where('starts_year', '=', date('Y'));
                                                $query->where('starts_day', '<=', (date('z')+1));
                                                $query->orWhere('starts_year', '<', (date('Y')));
                                            })
                                            ->where(function($query)
                                            {
                                                $query->where('expires_year', '=', date('Y'));
                                                $query->where('expires_day', '>=', (date('z')+1));
                                                $query->orWhere('expires_year', '>=', (date('Y')+1));
                                            })
                                            ->where('is_demo', '=', '0')
                                            ->where('is_active', '=', '1')
                                            ->count();
                $logo = SOE\DB\Asset::where('assetable_id', '=', $location->merchant_id)->where('assetable_type', '=', 'Merchant')->where('name', '=', 'logo1')->first();
                $loc = Location::blank();
                $loc = $loc->createFromModel($location);
                $loc->logo = empty($logo) ? '' : $logo->path;
                $loc->total_entities = $entities;
                $results['objects'][] = $loc;
            }
            return array_merge($results, $stats);
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
     * Retrieve an array of user clips given user_id, is_dailydeal, limit, page.
     *
     * @api
     *
     * @return mixed Formatted array of clipped offers.
     */
    public function apiGetClips()
    {
        //$filters = $this->getFilters();
        $user_id = Input::get('user_id');
        $this->find($user_id);
        $is_dailydeal = Input::get('is_dailydeal');
        $limit = Input::get('limit', 0);
        $page = Input::get('page', 0);
        return $this->format($this->getClips(/*$filters, */$is_dailydeal, $limit, $page));
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
     * Print an Entity based on user_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user print.
     */
    public function apiPrintEntity()
    {
        $user_id = Input::get("user_id");
        $entity_id = Input::get("entity_id");
        $offer_rand = Input::get("offer_rand", null);
        $this->find($user_id);
        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            return $this->format($this->printEntity($entity, $offer_rand));
        }
    }

    /**
     * Redeem an Entity based on user_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user print.
     */
    public function apiRedeemEntity()
    {
        $user_id = Input::get("user_id");
        $entity_id = Input::get("entity_id");
        $offer_rand = Input::get("offer_rand", null);
        $this->find($user_id);
        $entity = Entity::find($entity_id);
        if(!empty($entity))
        {
            return $this->format($this->redeemEntity($entity, $offer_rand));
        }
    }

    /**
     * View an Entity based on user_id and entity_id.
     *
     * @api
     *
     * @return mixed Formatted user view.
     */
    public function apiViewEntity()
    {
        $user_id = Input::get("user_id");
        $entity_id = Input::get("entity_id");
        $this->find($user_id);
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
     * Clip an Offer based on user_id and offer_id.
     *
     * @api
     *
     * @return mixed $clip
     */
    public function apiClipOffer()
    {
        $user_id = Input::get('user_id');
        $offer_id = Input::get('offer_id');
        $offer = Offer::find($offer_id);
        $this->find($user_id);
        if($this->primary_key && !empty($offer))
        {
            return $this->format($this->clipOffer($offer));
        }
    }

    /**
     * Click a banner based on user_id and banner_id.
     *
     * @api
     *
     * @return void
     */
    public function apiClickBanner()
    {
        $user_id = Input::get('user_id');
        $banner_id = Input::get('banner_id');
        $user = $this->find($user_id);
        $banner = Banner::find($banner_id);
        if($this->primary_key && !empty($banner))
        {
            return $this->format($this->clickBanner($banner));
        }
    }

    /**
     * Apply for a contest based on user_id and contest_id.
     *
     * @api
     *
     * @return mixed Formatted contest application.
     */
    public function apiApplyForContest()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        $contest_id = Input::get('contest_id');
        $contest = Contest::find($contest_id);
        if($this->primary_key && !empty($contest))
        {
            return $this->format($this->applyForContest($contest));
        }
    }

    /**
     * Set a user location based on user_id and latitude, longitude, city_name, region_name.
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
        $user_id = Input::get('user_id');
        $this->find($user_id);
        if($this->primary_key)
        {
            return $this->format($this->setLocation($lat, $lng, $city, $region));
        }
    }

    /**
     * Write a review based on user_id, reviewable_type and reviewable_id.
     *
     * @api
     *
     * @return mixed Formatted review information.
     */
    public function apiWriteReview()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        $type = studly_case(Input::get('reviewable_type'));
        $reviewable_id = Input::get('reviewable_id');
        $reviewable = $type::find($reviewable_id);
        return $this->format($this->writeReview($reviewable));
    }

    /**
     * Get an array of recommended Entities based on user_id.
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
            $geoip = Zipcode::getClosestAsGeoip($lat, $lng);
        }
        else
        {
            $geoip = array();
        }

        $user_id = Input::get("user_id");
        $limit = Input::get('limit', 0);
        $this->find($user_id);
        return $this->format($this->getRecommendations($limit, $geoip));
    }

    /**
     * Get an array of favorite locations based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetFavoriteLocations()
    {
        $user_id = Input::get("user_id");
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 12);
        $this->find($user_id);
        return $this->format($this->getFavoriteLocations($page, $limit));
    }

    /**
     * Favorite an object based on user_id, favoritable_type, favoritable_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiFavorite()
    {
        $user_id = Input::get("user_id");
        $this->find($user_id);
        $type = Input::get('favoritable_type', 'location');
        $type = studly_case($type);
        $id = Input::get('favoritable_id', 0);
        $model = $type;
        $favoritable = $model::find($id);
        if(empty($favoritable))
            return;
        return $this->format($this->favorite($favoritable));
    }

    /**
     * Save a favorite location for a User based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiSaveLocation()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        return $this->format($this->saveLocation());
    }

    /**
     * Get the saved locations for a User based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetSavedLocations()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        return $this->format($this->getSavedLocations());
    }

    /**
     * Delete a saved location for a User based on user_id and location_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiDeleteSavedLocation()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        $location_id = Input::get('location_id');
        return $this->format($this->deleteSavedLocation($location_id));
    }

    /**
     * Get Contests entered by this User based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetEnteredContests()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        return $this->format($this->getEnteredContests());
    }

    /**
     * Get Contests nearby this User based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetNearbyContests()
    {
        $user_id = Input::get('user_id');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 3);
        $this->find($user_id);
        return $this->format($this->getNearbyContests($page, $limit));
    }

    /**
     * Vote on a review for this User based on user_id, review_id, vote.
     *
     * @api
     *
     * @return mixed
     */
    public function apiVoteOnReview()
    {
        $user_id = Input::get('user_id');
        $review_id = Input::get('review_id');
        $vote = Input::get('vote');
        $this->find($user_id);
        $review = Review::find($review_id);
        if(empty($review))
            return;
        return $this->format($this->voteOnReview($review, $vote));
    }

    /**
     * Share a shareable for this User based on user_id, sharer_name, shareable_id, shareable_type, type, from_email, emails, message.
     *
     * @api
     *
     * @return mixed
     */
    public function apiShare()
    {
        $user_id = Input::get('user_id');
        $shareable_id = Input::get('shareable_id');
        $shareable_type = Input::get('shareable_type');
        $type = Input::get('type');
        $emails = Input::get('emails', '');
        $message = Input::get('message', '');
        $this->find($user_id);
        /*switch ($shareable_type)
        {
            case 'entity':
                $shareable = Entity::find($shareable_id);
                break;
            
            default:
                # code...
                break;
        }*/
        $model = studly_case($shareable_type);
        $shareable = $model::find($shareable_id);
        $from_email = Input::get('from_email', $this->email);
        $sharer_name = Input::get('sharer_name', $this->name);
        if($emails != '')
            $params['emails'] = $emails;
        if($message != '')
            $params['message'] = $message;
        $params['from_email'] = $from_email;
        $params['sharer_name'] = $sharer_name;
        if(empty($shareable))
            return;
        return $this->format($this->share($shareable, $type, $params));
    }

    /**
     * Suggest a merchant based on user_id and merchant info.
     *
     * @api
     *
     * @return mixed
     */
    public function apiMakeSuggestion()
    {
        $params = Input::all();
        $user_id = 0;
        if(isset($params['user_id']))
        {
            $user_id = $params['user_id'];
            unset($params['user_id']);
        }
        $user = $this->find($user_id);
        return $this->format($this->makeSuggestion($params));
    }

    /**
     * Save a searched location for a user based on user_id, latitude, longitude, city, state, page, limit.
     *
     * @api
     *
     * @return mixed
     */
    public function apiSaveSearchLocation()
    {
        $user_id = Input::get('user_id');
        $this->find($user_id);
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $city = Input::get('city');
        $state = Input::get('state');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 5);
        return $this->format($this->saveSearchLocation($latitude, $longitude, $city, $state, $page, $limit));
    }

    public function apiValidateSignup()
    {
        $validator = Validator::make(
                Input::all(),
                array('first_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|confirmed',
                    'zipcode' => 'required'
                    )
            );
        if($validator->fails())
        {
            $messages = $validator->messages();
            $failed = $validator->failed();
            foreach($failed as $field => $rules)
            {
                $return[$field] = $messages->first($field);
            }
            return $this->format($return);
            return Redirect::to('/signup');
        }
        return $this->format(array('valid' => true));
    }
}

Event::listen('auth.login', function($user)
{
    // Handle login
});


<?php

class EloquentLocationRepository extends BaseEloquentRepository implements LocationRepository, ReviewableInterface, ViewableInterface, FavoritableInterface, RepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'is_demo',
        'is_active',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'hours',
        'phone',
        'website',
        'rating',
        'rating_count',
        'merchant_id',
        'division_id',
        'franchise_id',
        'company_id',
        'is_national',
        'created_by',
        'updated_by',
        'latm',
        'lngm',
        'merchant_name',
        'merchant_slug',
        'deleted_at',
        'about',
        'page_title',
        'keywords',
        'meta_description',
        'custom_website',
        'custom_website_text',
        'subheader',
        'redirect_number',
        'redirect_text',
        'display_name',
        'is_logo_specific',
        'is_banner_specific',
        'is_about_specific',
        'is_pdf_specific',
        'is_video_specific',
        'facebook',
        'twitter',
        'is_address_hidden',
        'custom_address_text',
        'is_24_hours'
    );

    protected $model = 'Location';

    /**
     * Review this location for a given user.
     *
     * @param PersonInterface $reviewer
     * 
     * @return Review
     */
    public function writeReview(PersonInterface $reviewer)
    {
        if($this->primary_key)
        {
            $review = Review::blank();
            $review->reviewable_id = $this->primary_key;
            $review->reviewable_type = 'Location';
            if($reviewer->getType() == 'User')
                $review->user_id = $reviewer->id;
            else
                $review->nonmember_id = $reviewer->id;
            $review->content = Input::get('content');
            $review->rating = Input::get('rating');
            $review->save();
            $reviewer_id = $reviewer->id;
            $reviewer_type = $reviewer->getType();
            $review_id = $review->id;
            Queue::push(function($job) use ($reviewer_id, $reviewer_type, $review_id)
            {
                $reviewer = $reviewer_type == 'User' ? User::find($reviewer_id) : Nonmember::find($reviewer_id);
                $review = Review::find($review_id);
                $rating = SOE\DB\Review::where('reviewable_type', '=', 'Location')
                                        ->where('reviewable_id', '=', $review->reviewable_id)
                                        ->where('is_deleted', '=', 0)
                                        ->avg('rating');
                $location = SOE\DB\Location::find($review->reviewable_id);
                if(!empty($location) && !empty($rating))
                {
                    $location->rating = $rating;
                    $location->rating_count = $location->rating_count + 1;
                    $location->save();
                }
                $job->delete();
            });

            $review->upvotes = 0;
            $review->votes = 0;
            $review->user = User::find($review->user_id);
            return $review;
        }
    }

    /**
     * View this Location for a given person.
     *
     * @param PersonInterface $viewer
     */
    public function view(PersonInterface $viewer)
    {
        if($this->primary_key)
        {
            $location_id = $this->primary_key;
            $geoip = json_decode(GeoIp::getGeoIp('json'));
            $user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            $viewer_id = $viewer->id;
            $viewer_type = $viewer->getType();
            if(\App::environment() == 'prod') {
                Queue::push(function($job) use ($viewer_id, $viewer_type, $location_id, $geoip, $user_agent)
                {
                    $viewer = $viewer_type == 'User' ? User::find($viewer_id) : Nonmember::find($viewer_id);
                    if(stristr($user_agent, 'bot') || stristr($user_agent, 'spider'))
                    {
                        $job->delete();
                        return;
                    }
                    $location = Location::find($location_id);
                    $merchant = Merchant::find($location->merchant_id);
                    $category = Category::find($merchant->category_id);
                    $subcategory = Category::find($merchant->subcategory_id);
                    $company = SOE\DB\Company::where('id','=',$location->company_id)->first();

                    $mp = Mixpanel::getInstance(Config::get('integrations.mixpanel.token'));
                    $mp->identify($viewer->getType() == 'User' ? $viewer->email : 'non-'.$viewer->id);
                    $mp->track('Location View', array(
                        '$city' => $geoip->city_name,
                        'Environment' => App::environment(),
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
                        'UserType' => $viewer->getType()
                    ));

                    $view = UserView::blank();
                    if($viewer->getType() == 'User')
                    {
                        $view->user_id = $viewer->id;
                    }
                    else
                    {
                        $view->nonmember_id = $viewer->id;
                    }
                    $view->merchant_id = $location->merchant_id;
                    $view->franchise_id = $location->franchise_id;
                    $view->location_id = $location->id;
                    $view->user_agent = $user_agent;
                    $view->tracking_id = Cookie::get('tracking_id');
                    $view->url = Cookie::get('tracking_url');
                    $view->refer_id = Cookie::get('tracking_referid');
                    $view->save();
                    $job->delete();
                });
            }
        }
    }

    /**
     * Retrieve entities belonging to this location.
     *
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Entities.
     */
    public function getEntities($page = 0, $limit = 12)
    {
        if($this->primary_key)
        {
            $merchant = SOE\DB\Merchant::select(array('category_id'))->find($this->merchant_id);
            $query = SOE\DB\Entity::where('state', '=', $this->state)
                                    ->where('category_id', '=', $merchant->category_id)
                                    ->where('location_id', '=', $this->primary_key)
                                    ->where('is_active', '=', '1')
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
                                    });
            $person = Auth::person();
            if($person->showDemo() == false)
            {
                $query = $query->where('is_demo', '=', 0);
            }
            $stats = $this->getStats(clone $query, $limit, $page);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $query = $query->orderBy('importance');
            $entities = $query->remember(Config::get('soe.cache', 60*60*24))->get(array('entities.*', DB::raw("IF(is_dailydeal = 1, 0, IF(entitiable_type = 'Contest', 1, 2)) as importance")));
            $aEntIDs = array(0);
            foreach($entities as $entity)
            {
                if($entity->entitiable_type == 'Offer')
                {
                    $aEntIDs[] = $entity->entitiable_id;
                }
            }
            $aClipIDs = array(0);
            $clips = array();
            if($person->getType() == 'User')
            {
                $clips = SOE\DB\UserClipped::on('mysql-write')
                                            ->whereIn('offer_id', $aEntIDs)
                                            ->where('user_id', '=', $person->id)
                                            ->where('is_deleted', '=', '0')
                                            ->get(array('offer_id'));
            }
            foreach($clips as $clip)
            {
                $aClipIDs[] = $clip->offer_id;
            }
            $stats['stats']['returned'] = count($entities);
            $results = array('objects' => array());
            foreach($entities as $entity)
            {
                $ent = Entity::blank();
                $ent = $ent->createFromModel($entity);
                $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
                $results['objects'][] = $ent;
            }
            $results = array_merge($results, $stats);
            return $results;
        }
    }

    /**
     * Retrieve review belonging to this location.
     *
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Reviews.
     */
    public function getReviews($page = 0, $limit = 12)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\Review::where('reviewable_id', '=', $this->primary_key)
                                ->where('reviewable_type', '=', 'Location')
                                ->where('is_deleted', '=', '0')
                                ->orderBy('upvotes', 'desc')
                                ->orderBy('created_at', 'desc');
            $stats = $this->getStats(clone $query, $limit, $page);
            if($limit)
            {
                $query = $query->take($limit)->skip($limit*$page);
            }
            $reviews = $query->get();
            $stats['stats']['returned'] = count($reviews);
            $results = array('objects' => array());
            foreach($reviews as $review)
            {
                $upvotes = SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '1')->count();
                $downvotes = SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '-1')->count();
                $myvote = SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0);
                if(Auth::Check())
                {
                    $myvote = $myvote->where('user_id', '=', Auth::User()->id);
                }
                else
                {
                    $myvote = $myvote->where('nonmember_id', '=', Auth::Nonmember()->id);
                }
                $myvote = $myvote->first();
                $rev = Review::blank();
                $rev = $rev->createFromModel($review);
                $rev->votes = $upvotes + $downvotes;
                $rev->upvotes = $upvotes;
                $rev->user = User::find($review->user_id);
                $rev->my_vote = empty($myvote) ? 0 : $myvote->vote;
                $results['objects'][] = $rev;
            }
            $results = array_merge($results, $stats);
            return $results;
        }
    }

    public function getWithMerchant(array $filters = array(), $page = 0, $limit = 0)
    {
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $query = SOE\DB\Location::query();
        $query = $this->parseFilters($query, $filters);
        $query = $query->join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                        ->leftJoin('assets', function($join)
                        {
                            $join->on('assets.assetable_id', '=', 'merchants.id');
                            $join->on('assets.assetable_type', '=', DB::raw("'Merchant'"));
                            $join->on('assets.name', '=', DB::raw("'logo1'"));
                        });
        $stats = $this->getStats(clone $query, $page, $limit);
        if($limit)
        {
            $query = $query->skip($page*$limit)->take($limit);
        }
        $locations = $query->orderBy('distance')
                            ->remember(Config::get('soe.cache', 60*60*24))
                            ->get(array('locations.*', 
                                DB::raw('assets.path as logo'), 
                                DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $stats['stats']['returned'] = count($locations);
        $results = array('objects' => array());
        foreach($locations as $location)
        {
            $loc = Location::blank();
            $loc = $loc->createFromModel($location);
            $loc->distance = $loc->distance / 1609.34;
            $results['objects'][] = $loc;
        }
        return array_merge($results, $stats);
    }

    /**
     * Favorite this location for the given Person
     *
     * @param PersonInterface   $person
     * @return UserFavorite
     */
    public function favorite(PersonInterface $person)
    {
        $old = SOE\DB\UserFavorite::where('favoritable_type', '=', 'SOE\\DB\\Location')
                                ->where('favoritable_id', '=', $this->primary_key)
                                ->where('user_id', '=', $person->id)
                                ->where('is_deleted', '=', '0')
                                ->first();
        if(!empty($old))
        {
            $fav = UserFavorite::blank();
            $fav = $fav->createFromModel($old);
            return $fav;
        }
        $fav = UserFavorite::blank();
        $fav->user_id = $person->id;
        $fav->favoritable_type = 'SOE\\DB\\Location';
        $fav->favoritable_id = $this->primary_key;
        $fav->save();
        return $fav;
    }

    /**
     * Get locations by franchise_id
     *
     * @param int   $franchise_id
     * @param int   $page
     * @param int   $limit
     * @return mixed
     */
    public function getByFranchiseId($franchise_id, $page = 0, $limit = 0, $params = array())
    {
        $locations = SOE\DB\Location::where('franchise_id', '=', $franchise_id)->orderBy('name', 'asc');
        if(isset($params['filter']))
        {
            $locations = $locations->where('name', 'LIKE', '%'.$params['filter'].'%');
        }
        if(isset($params['is_active']) && $params['is_active'] != -1)
        {
            $locations = $locations->where('is_active', '=', $params['is_active']);
        }
        if(isset($params['is_deleted']) && $params['is_deleted'] != 1)
        {
            $locations = $locations->withTrashed();
        }
        $stats = $this->getStats(clone $locations, $limit, $page);
        if($limit)
        {
            $locations = $locations->take($limit)->skip($limit*$page);
        }
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

    /**
     * Get the franchise that this location belongs to.
     *
     * @return FranchiseRepository
     */
    public function franchise()
    {
        if($this->primary_key)
            return Franchise::find($this->franchise_id);
    }
    
    public function updateIndex()
    {
        if($this->primary_key && App::environment() == 'prod')
        {
            Artisan::call('search', array('--type' => 'merchant', '--id' => $this->primary_key));
        }
    }

    /***** API METHODS *****/

    /**
     * Retrieve entities belonging to a location based on location_id, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of entities.
     */
    public function apiGetEntities()
    {
        $location_id = Input::get('location_id');
        $this->find($location_id);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 12);
        return $this->format($this->getEntities($page, $limit));
    }

    /**
     * Retrieve entities belonging to a location based on location_id, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of entities.
     */
    public function apiGetReviews()
    {
        $location_id = Input::get('location_id');
        $this->find($location_id);
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 12);
        return $this->format($this->getReviews($page, $limit));
    }

    /**
     * Favorite a location based on location_id, user_id
     *
     * @api
     *
     * @return mixed Formatted favorite.
     */
    public function apiFavorite()
    {
        $location_id = Input::get('location_id');
        $this->find($location_id);
        $user_id = Input::get('user_id');
        $user = User::find($user_id);
        if(empty($user))
            return;
        return $this->format($this->favorite($user));
    }

    /**
     * Get locations by franchise_id.
     *
     * @api
     *
     * @return mixed Formatted locations.
     */
    public function apiGetByFranchiseId()
    {
        $franchise_id = Input::get('franchise_id');
        $params = Input::all();
        return $this->format($this->getByFranchiseId($franchise_id, Input::get('page', 0), Input::get('limit', 0), $params));
    }

}

/**
 * Handle the Location updated event.
 *
 * @param SOE\DB\Location $merchant
 * @return void
 */
SOE\DB\Location::updated(function($location)
{
    $location_id = $location->id;
    Queue::push(function($job) use ($location_id)
    {
        $location = Location::find($location_id);
        $location->updateIndex();
        $job->delete();
    });
});

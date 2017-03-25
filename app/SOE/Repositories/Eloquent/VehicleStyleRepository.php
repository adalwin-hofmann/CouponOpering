<?php namespace SOE\Repositories\Eloquent;

class VehicleStyleRepository extends BaseRepository implements \VehicleStyleRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'year',
        'model_name',
        'model_id',
        'make_name',
        'make_id',
        'model_year_id',
        'price',
        'primary_body_type',
        'edmunds_link',
        'edmunds_id',
        'body_type_id',
        'style_type_id',
        'city_epa',
        'highway_epa',
        'combined_epa',
        'engine_name',
        'transmission',
        'slug',
        'make_slug',
        'model_slug',
        'rating',
        'rating_count',
        'body_type',
        'popularity_score',
    );

    protected $model = 'VehicleStyle';

    public function __construct()
    {
        parent::__construct();
    }

    public function search($year = null, $make = null, $model = null, $min = null, $max = null, $page = 0, $limit = 0, $order = null, $body = null, $images = 0)
    {
        $query = $this->with(/*'incentives', 'assets',*/ 'displayImage');
        if($year)
            $query->years($year);
        else
            $query->years(array(date('Y'), date('Y', strtotime('+1 year'))));
        if($make)
            $query->makes($make);
        if($model)
            $query->models($model);
        if($min || $max)
            $query->price($min, $max);
        if($order == 'rand')
            $query->orderBy(\DB::raw('RAND()'));
        if($order == 'mpg')
            $query->orderBy('vehicle_styles.highway_epa','desc');
            
        if($body)
            $query->where('vehicle_styles.body_type', $body);

        $query->distinctModel();
        //$query->activeIncentives();
        $stats = $this->getStats(clone $query, $limit, $page, true);
        $query->select(array('vehicle_styles.*'));
        if($order == 'popularity')
        {
            $query->leftJoin(\DB::raw('vehicle_styles vs'), function($join)
            {
                $join->on('vehicle_styles.model_id', '=', 'vs.model_id');
                $join->on('vehicle_styles.popularity_score', '<', 'vs.popularity_score');
            })
            ->whereNull('vs.id');
            $query->orderBy('popularity_score','desc');
        }
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        foreach($results as &$result)
        {
            $incentive = $result->incentives()->orderBy('vehicle_incentives.rebate_amount', 'desc')->first();
            $result->incentives = $incentive ? array($incentive->toArray()) : array();
            $asset = $result->assets()->first();
            if($images)
            {
                $result->all_assets = $result->assets->toArray();
            }
            $result->assets = $asset ? array($asset->toArray()) : array();

        }
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    public function searchRelated($year = null, $make = null, $model = null, $min = null, $max = null, $page = 0, $limit = 0, $order = null)
    {
        $query = $this->with(/*'incentives', 'assets',*/ 'displayImage');
        if(!$year)
            $year = date('Y');
        if($make)
            $query->makes($make);
        if($model)
            $query->models($model);

        $query->distinctYear();
        $query->orderBy(\DB::raw('abs(vehicle_styles.year -'.$year.')'));
        $stats = $this->getStats(clone $query, $limit, $page, true);
        $query->select(array('vehicle_styles.*'));
        if($limit)
            $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        foreach($results as &$result)
        {
            $incentive = $result->incentives()->orderBy('vehicle_incentives.rebate_amount', 'desc')->first();
            $result->incentives = $incentive ? array($incentive->toArray()) : array();
            $asset = $result->assets()->first();
            $result->assets = $asset ? array($asset->toArray()) : array();
        }
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

    public function getRandom($make_id)
    {
        $query = \SOE\DB\VehicleStyle::where('vehicle_styles.make_id', '=', $make_id)->where('vehicle_styles.year','=',date("Y"));
        $query = $query->join('vehicle_assets', function($join)
                {
                    $join->on('vehicle_styles.id', '=', 'vehicle_assets.style_id')
                         ->on('shot_type', '=', \DB::raw("'FQ'"));
                });
        $query = $query->join('vehicle_models', function($join)
                {
                    $join->on('vehicle_styles.model_id', '=', 'vehicle_models.id')
                         ->on('about', '!=', \DB::raw("''"));
                });
        $results = $query->orderBy(\DB::raw('RAND()'))->first(array('vehicle_styles.*', 'vehicle_assets.path as path', 'vehicle_models.about as about'));
        return $results;
    }

    public function getIncentives($id, $page = 0, $limit = 0)
    {
        $query = $this->find($id)->incentives();
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query = $query->skip($page*$limit)->take($limit);
        $results = $query->get();
        $stats['stats']['returned'] = count($results);
        $return = array('objects' => $results);
        return array_merge($return, $stats);
        
        /*$query = $this->with('incentives', 'assets', 'displayImage');
        $query = $query->where('vehicle_styles.id', '=', $id)->first();
        $results = $query;
        $stats = $this->getStats(clone $query, $limit, $page, true);
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);*/
    }

    /**
     * Retrieve review belonging to this location.
     *
     * @param  int                  $page Default is 0.
     * @param  int                  $limit Number of results per page, default is 12.
     * 
     * @return array Reviews.
     */
    public function getReviews($id, $page = 0, $limit = 12)
    {
        $query = \SOE\DB\Review::where('reviewable_id', '=', $id)
                            ->where('reviewable_type', '=', 'SOE\DB\VehicleStyle')
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
            $upvotes = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '1')->count();
            $downvotes = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0)->where('vote', '=', '-1')->count();
            $myvote = \SOE\DB\ReviewVote::where('review_id', '=', $review->id)->where('is_deleted', '=', 0);
            if(\Auth::Check())
            {
                $myvote = $myvote->where('user_id', '=', \Auth::User()->id);
            }
            else
            {
                $myvote = $myvote->where('nonmember_id', '=', \Auth::Nonmember()->id);
            }
            $myvote = $myvote->first();
            $rev = \Review::blank();
            $rev = $rev->createFromModel($review);
            $rev->votes = $upvotes + $downvotes;
            $rev->upvotes = $upvotes;
            $rev->user = \User::find($review->user_id);
            $rev->my_vote = empty($myvote) ? 0 : $myvote->vote;
            $results['objects'][] = $rev;
        }
        $results = array_merge($results, $stats);
        return $results;
    }

    public function getByMakeModel($make, $model)
    {
        return $this->query()
                    ->makes($make)
                    ->models($model)
                    ->where('vehicle_styles.year','>=',date("Y"))
                    ->has('assets')
                    ->orderBy('year','desc')
                    ->first();
    }

    public function calculatePopularity()
    {
        \DB::table('vehicle_styles')->update(array('vehicle_styles.popularity_score' => 0));
        \DB::table('vehicle_styles') 
            ->leftJoin(\DB::raw('(select user_impressions.created_at, user_impressions.vehicle_style_id, count(*) as total from user_impressions group by vehicle_style_id) sp'), function($join)
            {
                $join->on('vehicle_styles.id', '=', 'sp.vehicle_style_id');
            })
            ->leftJoin(\DB::raw('(select user_impressions.created_at, user_impressions.vehicle_model_id, count(*) as total from user_impressions group by vehicle_model_id) mp'), function($join)
            {
                $join->on('vehicle_styles.model_id', '=', 'mp.vehicle_model_id');
            })
            ->where('vehicle_styles.year', '>', (date('Y')-3))
            ->where('sp.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL 60 DAY)'))
            ->where('mp.created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL 60 DAY)'))
            ->update(array('vehicle_styles.popularity_score' => \DB::raw('3 * sp.total + mp.total')));
    }
}


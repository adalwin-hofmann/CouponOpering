<?php namespace SOE\Services\Search;

use App;
use Auth;
use Config;
use GeoIp;
use SoeHelper;
use Location;
use Asset;
use Input;
use BaseEloquentRepository;
use Entity;
use SOE\DB\Entity as DBEntity;
use SOE\DB\UserClipped as UserClipped;
use SOE\DB\Location as DBLocation;
use DB;

class StdClass{}

class CloudSearch implements SearchInterface
{
    protected $format = 'json';
    protected $userRepository;
    protected $featureRepository;

    public function __construct(
        \UserRepositoryInterface $userRepository,
        \FeatureRepositoryInterface $featureRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->featureRepository = $featureRepository;
    }

    public function getResults($query, $type, $facet='', $facet_type='', $page = 0, $limit = 100, $order = 'distance', $filter_with_coupons = 'active', $with_radius = true)
    {
        $orig_query = $query;
        $query = str_replace("'", "", $query);
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $query = trim($query);
        $query = str_replace(' ', '*', $query);
        
        $radius = $this->featureRepository->findByName('search_dist');
        $radius = empty($radius) ? 60000 : $radius->value;

        $about_weight = $this->featureRepository->findByName('search_about_weight', false);
        $about_weight = $about_weight ? $about_weight->value : 2.38;
        $category_weight = $this->featureRepository->findByName('search_category_weight', false);
        $category_weight = $category_weight ? $category_weight->value : 2.38;
        $city_weight = $this->featureRepository->findByName('search_city_weight', false);
        $city_weight = $city_weight ? $city_weight->value : 2.38;
        $name_weight = $this->featureRepository->findByName('search_name_weight', false);
        $name_weight = $name_weight ? $name_weight->value : 2.38;
        $subcategory_weight = $this->featureRepository->findByName('search_subcategory_weight', false);
        $subcategory_weight = $subcategory_weight ? $subcategory_weight->value : 2.38;

        $data = array('q' => $query,
                    'rank' => '-expr_ranking',
                    'facet' => 'city,subcategory', 
                    'start' => $page*$limit, 
                    'size' => $limit,
                    'rank-expr_ranking' => 'cs.text_relevance({"weights":{"about":'.$about_weight.',"category":'.$category_weight.',"city":'.$city_weight.',"name":'.$name_weight.',"subcategory":'.$subcategory_weight.'},"default_weight":1})',
                    'rank-geo' => 'sqrt(pow(abs('.$cartesian['latm'].'-latm),2)+pow(abs('.$cartesian['lngm'].'-longm),2))',
                    );
        if($with_radius)
        {
            $data['bq'] = '(and latm:'.floor(abs($cartesian['latm'])-$radius).'..'.floor(abs($cartesian['latm'])+$radius).' longm:'.floor(abs($cartesian['lngm'])-$radius).'..'.floor(abs($cartesian['lngm'])+$radius);
        }
        else
        {
            $data['bq'] = '(and';
        }
        if($type == 'merchant')
        {
            switch ($facet_type) {
                case 'subcategory':
                    $facet = str_replace('&amp;', '&', $facet);
                    $data['bq'] .= " (field subcategory '".$facet."')";
                    break;
                case 'city':
                    $facet = str_replace('&amp;', '&', $facet);
                    $data['bq'] .= " (field city '".$facet."')";
                    break;
            }
            switch ('all')//$filter_with_coupons) 
            {
                case 'all':
                    $data['bq'] = $data['bq'];
                    break;
                case 'active':
                    $data['bq'] .= " (field is_active '1')";
                    break;
                case 'inactive':
                    $data['bq'] .= " (field is_active '0')";
                    break;
            }
            $data['return-fields'] = 'id,expr_ranking,geo,latm,longm';
            $base_url = Config::get('integrations.cloud_search.locations.base_url');
        }
        else if($type == 'entity')
        {
            $data['return-fields'] = 'eid,expr_ranking,geo,latm,longm';
            $base_url = Config::get('integrations.cloud_search.entities.base_url');
        }   
        else
        {
            switch ($facet_type) {
                case 'subcategory':
                    $facet = str_replace('&amp;', '&', $facet);
                    $data['bq'] .= " (field subcategory '".$facet."')";
                    break;
                case 'city':
                    $facet = str_replace('&amp;', '&', $facet);
                    $data['bq'] .= " (field city '".$facet."')";
                    break;
            }
            switch ('all')//$filter_with_coupons) 
            {
                case 'all':
                    $data['bq'] = $data['bq'];
                    break;
                case 'active':
                    $data['bq'] .= " (field is_active '1')";
                    break;
                case 'inactive':
                    $data['bq'] .= " (field is_active '0')";
                    break;
            }
            $data['return-fields'] = 'id,expr_ranking,geo,latm,longm';
            $base_url = Config::get('integrations.cloud_search.locations.base_url');
        }     
        $data['bq'] .= ')';
        if($data['bq'] == '(and)')
            unset($data['bq']);
        $base_url .= '?'.http_build_query($data);
        $session = curl_init();
        curl_setopt($session, CURLOPT_URL, $base_url);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($session, CURLOPT_VERBOSE, 1);

        $response = curl_exec($session);
        $status = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        
        if($status != 200)
        {
            return array('objects' => array(), 'stats' => array('total' => 0, 'take' => $limit, 'page' => $page, 'returned' => 0));
        }

        $response = json_decode($response);
        //print_r($response);exit;
        $aIDs = array(0);
        $aRel = array();
        $sIDs = '0';
        foreach($response->hits->hit as $hit)
        {
            if($type == 'merchant' || $type != 'offer')
            {
                $aIDs[] = $hit->data->id[0];
                $sIDs .= ','.$hit->data->id[0];
                $aRel[$hit->data->id[0]] = $hit->data->expr_ranking[0];
            }
            else if($type == 'offer')
            {
                $aIDs[] = $hit->data->eid[0];
            }
        }
        if($type == 'merchant' || $type != 'offer')
        {
            $distance = DB::raw('sqrt(pow(abs('.$cartesian['latm'].'-locations.latm),2)+pow(abs('.$cartesian['lngm'].'-locations.lngm),2)) as distance');
            $locations = DBLocation::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                    ->join(DB::raw('categories cat'), 'merchants.category_id', '=', 'cat.id')
                                    ->join(DB::raw('categories subcat'), 'merchants.subcategory_id', '=', 'subcat.id')
                                    ->join('franchises', 'locations.franchise_id', '=', 'franchises.id')
                                    ->where('locations.is_active', '1')
                                    ->where('franchises.is_active', '1')
                                    ->whereIn('locations.id', $aIDs)
                                    ->groupBy('locations.address');
            switch ($order) {
                case 'popular':
                    $locations = $locations->orderBy('locations.rating', 'desc')
                                            ->orderBy('distance', 'asc');
                    break;
                case 'az':
                    $locations = $locations->orderBy('locations.merchant_name', 'asc')
                                            ->orderBy('distance', 'asc');
                    break;
                case 'za':
                    $locations = $locations->orderBy('locations.merchant_name', 'desc')
                                            ->orderBy('distance', 'asc');
                    break;
                case 'relevance':
                    $locations = $locations;
                    break;
                default:
                    $locations = $locations->orderBy('distance');
                    break;
            }
            $user = Auth::User();
            if(!Auth::check() || (Auth::check() && !$this->userRepository->showDemo($user)))
            {
                $locations = $locations->where('locations.is_demo', '=', 0)
                                        ->where('franchises.is_demo', '=', 0);
            }

            $locations = $locations->remember(Config::get('soe.cache', 60*60*24))->get(array('locations.*', $distance, DB::raw('cat.slug as category_slug'), DB::raw('subcat.slug as subcategory_slug')));

            $aMerchIds = array(0);
            foreach($locations as $location)
            {
                $aMerchIds[] = $location->merchant_id;
            }

            $logos = DB::table('assets')->where('assets.assetable_type', '=', 'Merchant')
                                        ->whereIn('assetable_id', $aMerchIds)
                                        ->where('name', '=', 'logo1')
                                        ->remember(Config::get('soe.cache', 60*60*24))
                                        ->get();
            $aLogos = array();
            $aLogoIds = array(0);
            foreach($logos as $logo)
            {
                $aLogos[$logo->assetable_id] = $logo->path;
                $aLogoIds[] = $logo->assetable_id;
            }

            $missing = array_diff($aMerchIds, $aLogoIds);
            $missing[] = 0;
            $missing_logos = DB::table('offers')->whereIn('merchant_id', $missing)->groupBy('merchant_id')->get(array('merchant_id', 'path', 'path_small'));
            foreach($missing_logos as $logo)
            {
                $aLogos[$logo->merchant_id] = empty($logo->path) ? $logo->path_small : $logo->path;
            }

            $aLocIds = array(0);
            foreach($locations as $location)
            {
                $aLocIds[] = $location->id;
            }
            $aFavIds = array(0);
            if(Auth::check())
            {
                $favorites = DB::table('user_favorites')->where('user_id', '=', Auth::User()->id)
                                                        ->whereIn('favoritable_id', $aLocIds)
                                                        ->where('favoritable_type', '=', 'SOE\\DB\\Location')
                                                        ->get();
                foreach($favorites as $favorite)
                {
                    $aFavIds[] = $favorite->favoritable_id;
                }
            }

            $aLocLogos = array();
            $locLogos = DB::table('assets')->where('assets.assetable_type', '=', 'Location')
                                        ->whereIn('assetable_id', $aLocIds)
                                        ->where('name', '=', 'logo1')
                                        ->remember(Config::get('soe.cache', 60*60*24))
                                        ->get();
            foreach($locLogos as $locLogo)
            {
                $aLocLogos[$locLogo->assetable_id] = $locLogo->path;
            }

            $results = array('objects' => array());
            foreach($locations as $location)
            {
                $location->logo = !empty($location->logo) ? $location->logo : (isset($aLocLogos[$location->id]) ? $aLocLogos[$location->id] : (isset($aLogos[$location->merchant_id]) ? $aLogos[$location->merchant_id] : ''));
                $loc = Location::blank();
                $loc = $loc->createFromModel($location);
                $loc->distance = $loc->distance / 1609.34;
                $loc->is_favorite = in_array($location->id, $aFavIds) ? 1 : 0;
                $loc->expr_ranking = $aRel[$location->id];
                $results['objects'][] = $loc;
            }

            if($order == 'relevance' && count($results['objects']))
                $this->quickSort($results['objects']);

            $results['stats']['total'] = count($results['objects']) ? $response->hits->found : 0;
            $results['stats']['take'] = $limit;
            $results['stats']['page'] = $page;
            $results['stats']['returned'] = count($results['objects']);
            $results['stats']['facets'] = $response->facets;

            // Log search
            $this->logSearch($orig_query, $response->hits->found, $geoip);
        }
        else if($type == 'offer')
        {
            
            $query = DBEntity::whereIn('id', $aIDs)
                            ->groupBy('entitiable_id')
                            ->groupBy('entitiable_type')
                            ->orderBy('distance', 'asc');
            $query = $query->where('state', '=', $geoip->region_name);
            if(\SoeHelper::isTrackable())
            {
                $query = $query->where('is_demo', '=', 0);
            }
            $entities = $query->remember(Config::get('soe.cache', 60*60*24))->get(array(
                                    'entities.*',
                                    DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2))) as distance')
                                ));
            $stats = array('stats' => array());
            $stats['stats']['total'] = $response->hits->found;
            $stats['stats']['take'] = $limit;
            $stats['stats']['page'] = $page;
            $stats['stats']['facets'] = $response->facets;

            $stats['stats']['returned'] = count($entities);
            $results = array('objects' => array());
            $aEntIDs = array(0);
            foreach($entities as $entity)
            {
                if($entity->entitiable_type == 'Offer')
                {
                    $aEntIDs[] = $entity->entitiable_id;
                }
            }
            
            $aClipIDs = array(0);
            if(Auth::check())
            {
                $clips = UserClipped::on('mysql-write')
                                            ->whereIn('offer_id', $aEntIDs)
                                            ->where('user_id', '=', Auth::User()->id)
                                            ->where('is_deleted', '=', '0')
                                            ->get(array('offer_id'));
                foreach($clips as $clip)
                {
                    $aClipIDs[] = $clip->offer_id;
                }
            }

            foreach($entities as $entity)
            {
                $ent = Entity::blank();
                $ent = $ent->createFromModel($entity);
                $ent->is_clipped = ($ent->entitiable_type == 'Offer' && in_array($ent->entitiable_id, $aClipIDs)) ? 1 : 0;
                $results['objects'][] = $ent;
            }
            $results = array_merge($results, $stats);
        }

        return $results;
    }

    protected function quickSort( &$array )
    {
        $cur = 1;
        $stack[1]['l'] = 0;
        $stack[1]['r'] = count($array)-1;

        do
        {
            $l = $stack[$cur]['l'];
            $r = $stack[$cur]['r'];
            $cur--;

            do
            {
                $i = $l;
                $j = $r;
                $tmp = $array[(int)( ($l+$r)/2 )];

                // partion the array in two parts.
                // left from $tmp are with smaller values,
                // right from $tmp are with bigger ones
                do
                {
                    while( $array[$i]->expr_ranking > $tmp->expr_ranking )
                        $i++;

                    while( $tmp->expr_ranking > $array[$j]->expr_ranking )
                        $j--;

                    // swap elements from the two sides
                    if( $i <= $j)
                    {
                        $w = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $w;

                        $i++;
                        $j--;
                    }

                }while( $i <= $j );

                if( $i < $r )
                {
                    $cur++;
                    $stack[$cur]['l'] = $i;
                    $stack[$cur]['r'] = $r;
                }
                $r = $j;

            }while( $l < $r );

        }while( $cur != 0 );
    }

    /**
     * Log the search query.
     *
     * @param string    $query The text query.
     * @param int       $count The number of search results returned.
     * @param object    $geoip Geoip object.
     */
    protected function logSearch($query, $count, $geoip)
    {
        $person = Auth::person();
        if(!$person->id)
            return;
        DB::table('user_searches')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'search_text' => $query,
            'city' => $geoip->city_name,
            'state' => $geoip->region_name,
            'latitude' => $geoip->latitude,
            'longitude' => $geoip->longitude,
            'results' => $count,
            'user_id' => Auth::check() ? $person->id : 0,
            'nonmember_id' => Auth::check() ? 0 : $person->id
        ));
    }
    
    /***** API METHODS *****/

    public function apiGetResults()
    {
        $query = Input::get('q');
        $type = Input::get('t');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 100);
        $facet = Input::get('facet', '');
        $facet_type = Input::get('facet_type', '');
        $order = Input::get('s');
        $filter_with_coupons = Input::get('filter_with_coupons', 'active');
        $with_radius = Input::get('with_radius');
        //print_r($this->getResults($query, $type, $page, $limit));exit;
        return $this->format($this->getResults($query, $type, $facet, $facet_type, $page, $limit, $order, $filter_with_coupons, $with_radius == 'true'));
    }

    public function blank()
    {
        return App::make('search');
    }

    /**
     * Format data for the response.
     *
     * @param mixed  $data
     * @return mixed $data
     */
    protected function format($data)
    {
        $this->format = $this->format ? $this->format : Input::get('format', '');
        switch ($this->format)
        {
            case 'json':
                $return = new StdClass;
                if(is_array($data) && isset($data['objects']))
                {
                    $return->data = array();
                    foreach($data['objects'] as &$object)
                    {
                        $object = $this->checkForRepo($object);
                        $return->data[] = $object->attributes;
                    }
                    $return->stats = $data['stats'];
                }
                else if($data instanceof BaseEloquentRepository)
                {
                    $data = $this->checkForRepo($data);
                    $return = $data->attributes;
                }
                else
                {
                    $return = $data;
                }
                return json_encode($return);
                break;
            
            default:
                return $data;
                break;
        }
    }

    /**
     * Recursively search through an objects attributes to convert all Repositories to an array of attributes.
     *
     * @param mixed $object
     * @return mixed $object
     */
    protected function checkForRepo($object)
    {
        foreach($object->attributes as &$attr)
        {
            if($attr instanceof BaseEloquentRepository)
            {
                $attr = $this->checkForRepo($attr);
                $attr = $attr->attributes;
            }
        }
        return $object;
    }
}
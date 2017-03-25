<?php

use SOE\DB\Ipcache as IPCache;

class EloquentZipcodeRepository extends BaseEloquentRepository implements ZipcodeRepository, RepositoryInterface
{
    protected $columns = array(
        'recordnumber',
        'zipcode',
        'zipcodetype',
        'city',
        'state',
        'locationtype',
        'latitude',
        'longitude',
        'taxreturns2008',
        'estimatedpopulation',
        'totalwages',
        'latm',
        'lngm',
    );

    protected $model = 'Zipcode';

    /**
     * Retrieve zipcodes by search query.
     *
     * @param  string   $q The search query.
     * @param  int      $page Default 0.
     * @param  int      $limit Default 5.
     * @return mixed
     */
    public function getByQuery($q, $page = 0, $limit = 5)
    {
        $query = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD');
        if(is_numeric($q))
        {
            $query = $query->where('zipcode', 'LIKE', DB::raw("'".$q."%'"))
                            ->where('locationtype', '=', 'PRIMARY');
        }
        else
        {
            $q = str_replace(',', ' ', $q);
            $aPieces = explode(' ', $q);
            $q = '';
            foreach($aPieces as $index => &$piece)
            {
                $piece = trim($piece);
                if($piece == '')
                {
                    unset($aPieces[$index]);
                }
            }
            $q = implode(' ', $aPieces);
            $query = $query->where(DB::raw("CONCAT(city,' ',state)"), 'LIKE', strtoupper($q).'%');
            $query = $query->where(function($query)
            {
                $query->where('locationtype', '=', 'PRIMARY');
                $query->orWhere('locationtype', '=', 'ACCEPTABLE');
            });
        }
        //Filter Canada
        $query->whereNotIn('state', array('AB', 'BC', 'MB', 'NS', 'ON', 'QC'));

        $query = $query->orderBy('estimatedpopulation', 'desc');

        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->skip($limit*$page)->take($limit);
        }
        $zipcodes = $query->groupBy('city')
                        ->groupBy('state')
                        ->remember(Config::get('soe.cache', 60*60*24))
                        ->get();
        $stats['stats']['returned'] = count($zipcodes);
        $results = array('objects' => array());
        foreach($zipcodes as $zipcode)
        {
            $zip = Zipcode::blank();
            $zip->createFromModel($zipcode);
            $results['objects'][] = $zip;
        }
        return array_merge($results, $stats);
    }

    /**
     * Retrieve closest zip to a longitude and latitude
     *
     * @param $latitude int
     * @param $longitude int
     * @return Zipcode
     */
    public function getClosest($latitude, $longitude)
    {
        $cartesian = SoeHelper::getCartesian($latitude, $longitude);
        $distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        $zip = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
            ->where(function($query) {
                $query->where('locationtype', '=', 'PRIMARY');
                $query->orWhere('locationtype', '=', 'ACCEPTABLE');
            })
            ->whereNotIn('state', array('AB', 'BC', 'MB', 'NS', 'ON', 'QC'))
            ->orderBy($distance)
            ->first(array('zipcodes.*'));

        $ip = GeoIp::getIp();
        if ($ip != "127.0.0.1") {
            $ipc = \SOE\DB\Ipcache::where('ipaddress', $ip)->first();
            if(!$ipc)
            {
                $ipc = new \SOE\DB\Ipcache;
                $ipc->ipaddress     = $ip;
                $ipc->latitude      = $latitude;
                $ipc->longitude     = $longitude;
                $ipc->city          = $zip->city;
                $ipc->postalcode    = $zip->zipcode;
                $ipc->state         = $zip->state;
                $ipc->country       = 'US';
                try
                {
                    $ipc->save();
                }
                catch(\Exception $e)
                {
                    // IPCache record already exists
                }
            }
        }

        return $zip;
    }

    /**
     * Retrieve nearby zipcodes.
     *
     * @param  int   $page Default 0.
     * @param  int   $limit Default 5.
     * @return mixed
     */
    public function getNearby($page = 0, $limit = 5)
    {
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2))) as distance');
        $query = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
                                ->where('state','=',$geoip->region_name)
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                });

        //Filter Canada
        $query->whereNotIn('state', array('AB', 'BC', 'MB', 'NS', 'ON', 'QC'));
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
        {
            $query = $query->skip($limit*$page)->take($limit);
        }
        $zipcodes = $query->orderBy('distance')
                        ->groupBy('city')
                        ->groupBy('state')
                        ->remember(600)
                        ->get(array('zipcodes.*', $distance));
        $stats['stats']['returned'] = count($zipcodes);
        $results = array('objects' => array());
        foreach($zipcodes as $zipcode)
        {
            $zip = Zipcode::blank();
            $zip = $zip->createFromModel($zipcode);
            $results['objects'][] = $zip;
        }
        return array_merge($results, $stats);
    }

    /**
     * Retrieve the most populous cities withing the given state.
     *
     * @param string    $state Abbreviation of state.
     * @param int       $page Default 0.
     * @param int       $limit Default 0.
     * @param int       $min_population Default 15000.
     * @param boolean   $group Group the query results by city.
     * @return mixed
     */
    public function getTopByState($state, $page = 0, $limit = 0, $min_population = 15000, $group = true)
    {
        $cities = SOE\DB\Zipcode::where('state', '=', $state)
                                ->where('estimatedpopulation', '>', $min_population)
                                ->where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                })
                                ->orderBy('city');
        if($group)
        {
            $cities = $cities->groupBy('city');
        }
        $stats = $this->getStats(clone $cities, $limit, $page, true);
        $cities = $cities->get();
        $stats['stats']['returned'] = count($cities);
        $results = array('objects' => array());
        foreach($cities as $city)
        {
            $zip = Zipcode::blank();
            $zip = $zip->createFromModel($city);
            $results['objects'][] = $zip;
        }
        return array_merge($results, $stats);
    }

    /**
     * Get the zipcode entry nearest to the given latitude and longitude.
     *
     * @param float     $latitude Given in either degrees or cartesian coordinates.
     * @param float     $longitude Given in either degrees or cartesian coordinates.
     * @param boolean   $cartesian Whether or not the given latitude / longitude are cartesian coordinates.
     * @return Zipcode
     */
    public function getNearest($latitude, $longitude, $cartesian = false)
    {
        if(!$cartesian)
        {
            $cart = SoeHelper::getCartesian($latitude, $longitude);
            $latitude = $cart['latm'];
            $longitude = $cart['lngm'];
        }
        $zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$latitude.', 2) + pow(zipcodes.lngm - '.$longitude.', 2)))');
        $zipcode = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                })
                                ->orderBy($zip_distance, 'asc')
                                ->first();
        $zip = Zipcode::blank();
        $zip = $zip->createFromModel($zipcode);
        return $zip;
    }

    /**
     * Get any states that are within a configurable distance of a given latitude and longitude.
     *
     * @param float     $latitude
     * @param float     $longitude
     * @param int       $radius Radius given in meters, 68000 default.
     * @param boolean   $cartesian Coordinates given in cartesian form?
     * @return array    Array of nearby state abbreviations.
     */
    public function getSurroundingStates($latitude, $longitude, $radius = 68000, $cartesian = false)
    {
        if($cartesian == false)
        {
            $coords = SoeHelper::getCartesian($latitude, $longitude);
            $latitude = $coords['latm'];
            $longitude = $coords['lngm'];
        }
        $zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$latitude.', 2) + pow(zipcodes.lngm - '.$longitude.', 2)))');

        $states = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                })
                                ->where($zip_distance, '<', $radius)// Remove times when accuracy is not great
                                ->groupBy('state')
                                ->get(array('state'));
        $aStates = array();
        foreach($states as $state)
        {
            $aStates[] = $state->state;
        }

        return $aStates;
    }

    /***** API METHODS *****/

    /**
     * Retrieve zipcodes based on a query q, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of zipcodes.
     */
    public function apiGetByQuery()
    {
        $q = Input::get('q');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 5);
        return $this->format($this->getByQuery($q, $page, $limit));
    }

    /**
     * Retrieve nearby zipcodes based on page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of zipcodes.
     */
    public function apiGetNearby()
    {
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 5);
        return $this->format($this->getNearby($page, $limit));
    }

    /**
     * Retrieve the nearest zipcode based on latitude and longitude.
     *
     * @api
     *
     * @return mixed
     */
    public function apiGetNearest()
    {
        return $this->format($this->getNearest(Input::get('latitude'), Input::get('longitude'), Input::get('cartesian', false)));
    }

}

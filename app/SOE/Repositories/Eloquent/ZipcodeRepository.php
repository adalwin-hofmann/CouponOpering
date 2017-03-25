<?php namespace SOE\Repositories\Eloquent;

class ZipcodeRepository extends BaseRepository implements \ZipcodeRepositoryInterface, \BaseRepositoryInterface
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
        $cities = \SOE\DB\Zipcode::where('state', '=', strtoupper($state))
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
        $cities = $this->paginator($cities, $limit, $page);
        $cities = $cities->get();
        $aCities = array('objects' => array());
        foreach($cities as $city)
        {
            $aCities['objects'][] = $city;
        }
        $stats['stats']['returned'] = count($cities);
        return array_merge($aCities, $stats);
    }

    /**
     * Retrieve all zipcodes within a radius of the given latitude and longitude, optionally limited to a state.
     *
     * @param float     $lat
     * @param float     $lng
     * @param float     $raius The radius in meters.
     * @param string    $state Optional param to limit results to a specific state.
     * @return array
     */
    public function getByRadius($lat, $lng, $radius, $state = null)
    {
        $cartesian = \SoeHelper::getCartesian($lat, $lng);
        $zip_distance = \DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        $zips = \SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                })
                                ->where($zip_distance, '<', $radius)
                                ->groupBy('zipcode');
        if($state)
        {
            $zips = $zips->where('state', '=', $state);
        }
        return $zips->get();
    }

    /**
     * Retrieve a zipcode record by zipcode.
     *
     * @param integer   $zipcode
     * @return mixed
     */
    public function findByZipcode($zipcode)
    {
        $query = \SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
                                ->where(function($query)
                                {
                                    $query->where('locationtype', '=', 'PRIMARY');
                                    $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                });
        if(is_array($zipcode))
        {
            $result = $query->whereIn('zipcode', $zipcode)->get(array('zipcode', 'city', 'state', 'latitude', 'longitude', 'latm', 'lngm'));
        }
        else
        {
            $result = $query->where('zipcode', '=', $zipcode)->first();
        }
        return $result;
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
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
        $zip = \SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
            ->where(function($query) {
                $query->where('locationtype', '=', 'PRIMARY');
                $query->orWhere('locationtype', '=', 'ACCEPTABLE');
            })
            ->whereNotIn('state', array('AB', 'BC', 'MB', 'NS', 'ON', 'QC'))
            ->orderBy($distance)
            ->first(array('zipcodes.*'));

        $ip = \GeoIp::getIp();
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
     * Retrieve a zipcode based on a city, state.
     *
     * @param string    $city
     * @param string    $state
     * @return SOE\DB\Zipcode
     */
    public function getByCityState($city, $state, $latitude = null, $longitude = null)
    {
        $query = \SOE\DB\Zipcode::where('state', '=', strtoupper($state))
                            ->where('city', '=', $city)
                            ->where('zipcodetype', '=', 'STANDARD')
                            ->where(function($query)
                            {
                                $query->where('locationtype', '=', 'PRIMARY');
                                $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                            });
        if($latitude && $longitude)
        {
            $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
            $zip_distance = \DB::raw('(sqrt(pow(zipcodes.latm - '.$cartesian['latm'].', 2) + pow(zipcodes.lngm - '.$cartesian['lngm'].', 2)))');
            $query = $query->where($zip_distance, '<', 68000);// Remove times when accuracy is not great
        }
                            
        return $query->orderBy('estimatedpopulation', 'desc')
                    ->first(array('zipcodes.*'));
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
            $coords = \SoeHelper::getCartesian($latitude, $longitude);
            $latitude = $coords['latm'];
            $longitude = $coords['lngm'];
        }
        $zip_distance = \DB::raw('(sqrt(pow(zipcodes.latm - '.$latitude.', 2) + pow(zipcodes.lngm - '.$longitude.', 2)))');

        $states = \SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
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
        if(empty($aStates))
        {
            $aStates[] = 'MI';
        }

        return $aStates;
    }

}


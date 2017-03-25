<?php namespace SOE\Repositories\Eloquent;

class UserLocationRepository extends BaseRepository implements \UserLocationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'user_id',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'ip',
        'my_point',
        'nonmember_id',
        'latm',
        'lngm',
    );

    protected $model = 'UserLocation';

    public function __construct(
        \ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->zipcodes = $zipcodes;
        parent::__construct();
    }

    public function getMostRecent(\PersonInterface $user)
    {
        return $this->query()->where('user_id', $user->id)
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function saveLocation($user_id, $latitude = null, $longitude = null)
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        if($latitude)
        {
            $zipcode = $this->zipcodes->getClosest($latitude, $longitude);
            if($zipcode)
            {
                $geoip->city_name = $zipcode->city;
                $geoip->region_name = $zipcode->state;
                $geoip->latitude = $zipcode->latitude;
                $geoip->longitude = $zipcode->longitude;
            }
        }

        $location = $this->query()->where('user_id', '=', $user_id)
            ->where('is_deleted', '=', '0')
            ->where('city', '=', $geoip->city_name)
            ->where('state', '=', $geoip->region_name)
            ->first();
        if(empty($location))
        {
            $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
            $location = $this->create(array(
                'user_id' => $user_id,
                'city' => $geoip->city_name,
                'state' => $geoip->region_name,
                'latitude' => $geoip->latitude,
                'longitude' => $geoip->longitude,
                'latm' => $cartesian['latm'],
                'lngm' => $cartesian['lngm']
            ));
        }

        $query = $this->query()->where('user_id', '=', $user_id)
            ->where('is_deleted', '=', '0')
            ->orderBy('created_at', 'desc');
        $stats = $this->getStats(clone $query, 5, 0);
        $locations = $query->take(5)
                            ->get();
        $stats['stats']['returned'] = count($locations);
        $results = array('objects' => $locations);
        return array_merge($results, $stats);
    }

    public function setLocation(\PersonInterface $user, $latitude = '', $longitude = '', $city = '', $state = '', $use_current = false)
    {
        \Session::forget('nearby');
        \Session::forget('recent');
        $isUser = $user->getType() == 'User' ? true : false;

        if ($longitude && $latitude && $city && $state && ($longitude != 1 && $latitude != 1 && $city != 1 && $state != 1)) {
            $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
            $location = array(
                'city_name' => ucwords(strtolower(str_replace('-', ' ', $city))),
                'region_name' => strtoupper($state),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'use_current' => $use_current,
                'latm' => $cartesian['latm'],
                'lngm' => $cartesian['lngm']
            );
        } else {
            $location = array();

            if ($longitude && $latitude) {
                $geoip = $this->zipcodes->getClosest($latitude, $longitude);
                $location['city_name'] = ucwords(strtolower($geoip->city));
                $location['region_name'] = strtoupper($geoip->state);
            } else {
                $geoip = json_decode(\GeoIp::getGeoIp('json', true, ($isUser ? $user : null)));
                $location['city_name'] = ucwords(strtolower($geoip->city_name));
                $location['region_name'] = strtoupper($geoip->region_name);
            }
            $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);

            $location['latitude'] = $geoip->latitude;
            $location['longitude'] = $geoip->longitude;
            $location['latm'] = $cartesian['latm'];
            $location['lngm'] = $cartesian['lngm'];
            $location['use_current'] = $use_current;
        }

        if ($isUser) {
            $user->city = $location['city_name'];
            $user->state = $location['region_name'];
            $user->longitude = $location['longitude'];
            $user->latitude = $location['latitude'];
            $user->latm = $location['latm'];
            $user->lngm = $location['lngm'];
            $user->save();
        }

        \Cookie::queue('current_location', $location, 120); //2 hours
        \Session::put('user_location', $location);
        \Session::forget('affiliate');

        // Should this be here?
        $location['whitelabel'] = '';
        $whitelabel_dist = "( 3959 * acos( cos( radians(".$location['latitude'].") ) * cos( radians( companies.latitude ) )
               * cos( radians(companies.longitude) - radians(".$location['longitude'].")) + sin(radians(".$location['latitude']."))
               * sin( radians(companies.latitude))))";
        $whitelabel = \SOE\DB\Company::where('radius', '>', \DB::raw($whitelabel_dist))->orderBy('own_market')->get();
        if(count($whitelabel)) {
            if((count($whitelabel) == 1) || ($whitelabel[0]->own_market == 1)) {
                \Session::put('affiliate', $whitelabel[0]->slug);
                $location['whitelabel'] = $whitelabel[0]->slug;
            }
        }

        return $location;
    }
}
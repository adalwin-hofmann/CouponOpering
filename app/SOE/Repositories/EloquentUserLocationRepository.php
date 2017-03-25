<?php

class EloquentUserLocationRepository extends BaseEloquentRepository implements UserLocationRepository, RepositoryInterface
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
        'nonmember_id'
    );

    protected $model = 'UserLocation';

    public function setLocation($user, $latitude = '', $longitude = '', $city = '', $state = '', $use_current = false)
    {
        Session::forget('nearby');
        Session::forget('recent');
        $types = class_implements($user);
        $isUser = in_array('UserRepository', $types);

        if ($longitude && $latitude && $city && $state) {
            $location = array(
                'city_name' => ucwords(strtolower($city)),
                'region_name' => strtoupper($state),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'use_current' => $use_current
            );
        } else {
            $location = array();

            if ($longitude && $latitude) {
                $geoip = Zipcode::getClosest($latitude, $longitude);
                $location['city_name'] = ucwords(strtolower($geoip->city));
                $location['region_name'] = strtoupper($geoip->state);
            } else {
                $geoip = json_decode(GeoIp::getGeoIp('json', true, ($isUser ? $user : null)));
                $location['city_name'] = ucwords(strtolower($geoip->city_name));
                $location['region_name'] = strtoupper($geoip->region_name);
            }

            $location['latitude'] = $geoip->latitude;
            $location['longitude'] = $geoip->longitude;
            $location['use_current'] = $use_current;
        }

        if ($isUser) {
            $user->city = $location['city_name'];
            $user->state = $location['region_name'];
            $user->longitude = $location['longitude'];
            $user->latitude = $location['latitude'];
            $user->save();
        }

        Session::put('user_location', $location);
        Session::forget('affiliate');

        // Should this be here?
        $location['whitelabel'] = '';
        $whitelabel_dist = "( 3959 * acos( cos( radians(".$location['latitude'].") ) * cos( radians( companies.latitude ) )
               * cos( radians(companies.longitude) - radians(".$location['longitude'].")) + sin(radians(".$location['latitude']."))
               * sin( radians(companies.latitude))))";
        $whitelabel = SOE\DB\Company::where('radius', '>', DB::raw($whitelabel_dist))->orderBy('own_market')->get();
        if(count($whitelabel)) {
            if((count($whitelabel) == 1) || ($whitelabel[0]->own_market == 1)) {
                Session::put('affiliate', $whitelabel[0]->slug);
                $location['whitelabel'] = $whitelabel[0]->slug;
            }
        }

        return $location;
    }

}

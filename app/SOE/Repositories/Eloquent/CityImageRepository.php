<?php namespace SOE\Repositories\Eloquent;

class CityImageRepository extends BaseRepository implements \CityImageRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'zip',
        'state',
        'latitude',
        'longitude',
        'path',
        'img_415x258',
        'img_266x266',
        'about',
        'latm',
        'lngm',
        'display',
        'region_type'
    );

    protected $model = 'CityImage';

    /**
     * Get a city image by state.
     *
     * @param string $state
     * @return mixed
     */
    public function findByState($state)
    {
        return $this->query()->where('state', strtoupper($state))
                            ->where('region_type', 'State')
                            ->remember(600)
                            ->first();
    }

    public function getStates()
    {
        return $this->query()->where('region_type', 'State')
            ->orderBy('state')
            ->get();
    }

    public function getNearbyCity($state, $latitude, $longitude)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        return \SOE\DB\CityImage::where('state', '=', $state)
            ->where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                \DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
    }
}
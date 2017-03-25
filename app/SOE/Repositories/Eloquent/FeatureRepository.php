<?php namespace SOE\Repositories\Eloquent;

class FeatureRepository extends BaseRepository implements \FeatureRepositoryInterface, \BaseRepositoryInterface
{
        protected $columns = array(
        'type',
        'entity',
        'name',
        'value',
    );

    protected $model = 'Feature';

    /**
     * Retrieve a feature by name;
     *
     * @param string $name
     * @param boolean $remember Cache the query? Default true.
     * @return SOE\DB\Feature
     */
    public function findByName($name, $remember = true)
    {
        $query = \SOE\DB\Feature::where('name', '=', $name);
        if($remember)
            $query = $query->remember(\Config::get('soe.cache', 60*60*24));
        return $query->first();
    }

    public function mobileGpsEnabled()
    {
        $enabled = \SOE\DB\Feature::select('value')
            ->where('name', 'mobile_gps_location')
            ->first();

        if (is_null($enabled)) {
            return false;
        }

        return (bool)$enabled->value;
    }

    public function updateByName($name, $params)
    {
        $feature = \SOE\DB\Feature::where('name', '=', $name)->first();
        if(!$feature)
            return false;

        foreach($params as $key => $value)
        {
            if($key != 'name' && in_array($key, $this->columns))
            {
                $feature->$key = $value;
            }
            $feature->save();
        }
    }

}
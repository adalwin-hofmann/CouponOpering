<?php

class EloquentFeatureRepository extends BaseEloquentRepository implements FeatureRepository, RepositoryInterface
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
     * @return Feature
     */
    public function findByName($name)
    {
        $feature = SOE\DB\Feature::where('name', '=', $name)->first();
        if(!empty($feature))
        {
            $feat = Feature::blank();
            $feat = $feat->createFromModel($feature);
            return $feat;
        }
    }

    public function mobileGpsEnabled()
    {
        $enabled = SOE\DB\Feature::select('value')
            ->where('name', 'mobile_gps_location')
            ->first();

        if (is_null($enabled)) {
            return false;
        }

        return (bool)$enabled->value;
    }
}

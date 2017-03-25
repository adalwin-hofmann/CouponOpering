<?php namespace SOE\Repositories\Eloquent;

class YipitDivisionRepository extends BaseRepository implements \YipitDivisionRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'url',
        'country',
        'lon',
        'lat',
        'about',
        'is_active',
        'time_zone_diff',
        'slug',
    );

    protected $model = 'YipitDivision';

    public function getActive()
    {
        return \SOE\DB\YipitDivision::where('is_active', '=', '1')->get();
    }

}
<?php namespace SOE\Repositories\Eloquent;

class VehicleYearRepository extends BaseRepository implements \VehicleYearRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'year',
        'model_name',
        'model_id',
        'make_name',
        'make_id',
        'edmunds_default_style',
        'edmunds_link',
        'edmunds_id',
        'model_slug',
        'make_slug',
        'default_style_id',
        'state',
    );

    protected $model = 'VehicleYear';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByYearMakeModel($year,$make,$model)
    {
        return \SOE\DB\VehicleYear::where('year', '=', $year)->where('make_slug','=',$make)->where('model_slug','=',$model)->first();
    }

    public function getByModel($model)
    {
        $query = $this->with(array('vehicleStyles', 'vehicleStyles.assets'))->byModel($model);
        $query = $query->where('vehicle_years.year','>=','2004');
        $query = $query->join('vehicle_styles','vehicle_styles.model_year_id','=','vehicle_years.id');
        $query = $query->leftJoin('vehicle_assets', function($join)
                {
                    $join->on('vehicle_styles.id', '=', 'vehicle_assets.style_id')
                         ->on('shot_type', '=', \DB::raw("'FQ'"));
                });
        $query = $query->groupBy('vehicle_years.id');
        $get = array();
        $get[] = 'vehicle_years.*';
        $get[] = \DB::raw("IF(state = 'NEW', 1, 0) as is_new");
        $get[] = 'vehicle_assets.path as path';
        $query = $query->orderBy('is_new','desc');
        $query = $query->orderBy('vehicle_years.year', 'desc');
        $query = $query->get($get);
        foreach($query as &$q)
        {
            $q->vehicleStyles = $q->vehicleStyles->sortByDesc(function($model)
            {
                return count($model->assets);
            });
        }

        return $query;
    }

    /**
     * Retrieve all models for a give vehicle make.
     *
     * @param int $make The id of the vehicle make.
     * @return array
     */
    public function getNewByMake($make)
    {
        $query = $this->query()
                    ->byMakeSlug($make)
                    ->orderBy('model_name');
        $query->distinctModel();
        $query->where('vehicle_years.year','>=',date("Y"));
        $query = $query->get();;
        return $query;
    }
    
}


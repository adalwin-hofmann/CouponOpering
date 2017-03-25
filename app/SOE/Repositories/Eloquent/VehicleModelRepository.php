<?php namespace SOE\Repositories\Eloquent;

class VehicleModelRepository extends BaseRepository implements \VehicleModelRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'edmunds_id',
        'make_name',
        'make_id',
        'edmunds_link',
        'slug',
        'make_slug',
        'about',
    );

    protected $model = 'VehicleModel';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByName($slug)
    {
        return \SOE\DB\VehicleModel::where('slug', '=', $slug)->first();
    }

    /**
     * Retrieve all models for a give vehicle make.
     *
     * @param int $make The id of the vehicle make.
     * @return array
     */
    public function getByMake($make)
    {
        return $this->query()
                    ->byMakeSlug($make)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Retrieve all models for a give vehicle make.
     *
     * @param int $make_id The id of the vehicle make.
     * @return array
     */
    public function getByMakeId($make_id)
    {
        return $this->query()
                    ->where('make_id', $make_id)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Retrieve all models for a give vehicle make.
     *
     * @param int $make The id of the vehicle make.
     * @return array
     */
    public function getNewByMake($make)
    {
        return \SOE\DB\VehicleModel::join('vehicle_styles', 'vehicle_styles.model_id', '=', 'vehicle_models.id')
                    ->where('vehicle_models.make_slug', $make)
                    ->where(function($query){
                        $query->where('vehicle_styles.year', date('Y'))
                            ->orWhere('vehicle_styles.year', date('Y', strtotime('+1 year')));
                    })
                    ->groupBy('vehicle_styles.model_id')
                    ->orderBy('vehicle_styles.model_name')
                    ->get(array('vehicle_models.*'));
    }

    public function getNames()
    {
        return $this->query()->get(array('id', 'name'));
    }
    
}


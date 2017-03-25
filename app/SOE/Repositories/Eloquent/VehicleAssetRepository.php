<?php namespace SOE\Repositories\Eloquent;

class VehicleAssetRepository extends BaseRepository implements \VehicleAssetRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'type',
        'path',
        'name',
        'description',
        'short_description',
        'edmunds_style_id',
        'style_id',
        'shot_type',
    );

    protected $model = 'VehicleAsset';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve all models for a give vehicle make.
     *
     * @param int $make The id of the vehicle make.
     * @return array
     */
    public function getByStyle($style)
    {
        $query = $this->query()
                    ->byStyle($style);
        $get = array();
        $get[] = 'vehicle_assets.*';
        $get[] = \DB::raw("IF(shot_type = 'FQ', 1, 0) as is_fq");
        $query = $query->orderBy('is_fq','desc');
        $query = $query->orderBy('pic_size','desc');
        $query = $query->get($get);
        return $query;
    }
    
}


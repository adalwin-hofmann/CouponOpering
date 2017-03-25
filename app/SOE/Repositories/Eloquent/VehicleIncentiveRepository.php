<?php namespace SOE\Repositories\Eloquent;

class VehicleIncentiveRepository extends BaseRepository implements \VehicleIncentiveRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'slug',
        'rebate_amount',
        'edmunds_id',
        'type',
        'content_type',
        'incentive_type',
        'starts_at',
        'expires_at',
        'description',
        'restrictions',
        'make_name',
        'make_id',
        'is_active',
    );

    protected $model = 'VehicleIncentive';

    public function __construct()
    {
        parent::__construct();
    }
    
}


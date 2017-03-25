<?php 


class EloquentVehicleIncentiveRepository extends BaseEloquentRepository implements VehicleIncentiveRepository, RepositoryInterface
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
}
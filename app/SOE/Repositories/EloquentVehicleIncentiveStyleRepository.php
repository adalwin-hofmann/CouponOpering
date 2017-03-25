<?php 


class EloquentVehicleIncentiveStyleRepository extends BaseEloquentRepository implements VehicleIncentiveStyleRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
        'vehicle_incentive_id',
        'vehicle_style_id',
    );

    protected $model = 'VehicleIncentiveStyle';
}
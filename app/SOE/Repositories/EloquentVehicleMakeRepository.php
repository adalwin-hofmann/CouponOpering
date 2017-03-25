<?php 


class EloquentVehicleMakeRepository extends BaseEloquentRepository implements VehicleMakeRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'slug',
        'edmunds_id',
        'is_active',
        'old_id',
    );

    protected $model = 'VehicleMake';
}
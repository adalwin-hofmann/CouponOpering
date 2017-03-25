<?php 


class EloquentVehicleModelRepository extends BaseEloquentRepository implements VehicleModelRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'edmunds_id',
        'make_name',
        'make_id',
        'edmunds_link',
        'make_slug',
        'slug',
        'about',
    );

    protected $model = 'VehicleModel';
}
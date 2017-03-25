<?php 


class EloquentVehicleYearRepository extends BaseEloquentRepository implements VehicleYearRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
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
    );

    protected $model = 'VehicleYear';
}
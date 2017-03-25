<?php 


class EloquentVehicleAssetRepository extends BaseEloquentRepository implements VehicleAssetRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
        'type',
        'path',
        'name',
        'description',
        'short_description',
        'edmunds_style_id',
        'style_id',
        'shot_type',
        'pic_size',
    );

    protected $model = 'VehicleAsset';
}
<?php 


class EloquentVehicleCommandHistoryRepository extends BaseEloquentRepository implements VehicleCommandHistoryRepository, RepositoryInterface
{
    protected $columns = array(
        'id',
        'is_finished',
        'command_name',
        'last_query',
        'last_query_id',
    );

    protected $model = 'VehicleCommandHistory';
}
<?php

class EloquentLocationHourRepository extends BaseEloquentRepository implements LocationHourRepository, RepositoryInterface
{
    protected $columns = array(
        'weekday',
        'start_time',
        'start_ampm',
        'end_time',
        'end_ampm',
        'location_id',
    );

    protected $model = 'LocationHour';

}
<?php namespace SOE\Repositories\Eloquent;

class SysLogRepository extends BaseRepository implements \SysLogRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'type',
        'message'
    );

    protected $model = 'SysLog';
}
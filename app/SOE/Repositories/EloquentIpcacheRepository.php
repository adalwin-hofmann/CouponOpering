<?php

class EloquentIpcacheRepository extends BaseEloquentRepository implements IpcacheRepository, RepositoryInterface
{
    protected $columns = array(
        'ipaddress',
        'postalcode',
        'city',
        'state',
        'country',
        'longitude',
        'latitude',
    );

    protected $model = 'Ipcache';

}
<?php namespace SOE\Repositories\Eloquent;

class AdImpressionRepository extends BaseRepository implements \AdImpressionRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'advertisement_id',
        'user_id',
        'nonmember_id',
        'city',
        'state',
        'latitude',
        'longitude',
        'latm',
        'lngm',
        'url',
    );

    public function __construct()
    {
        parent::__construct();   
    }

    protected $model = 'AdImpression';

    
}
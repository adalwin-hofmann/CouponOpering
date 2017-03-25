<?php

class EloquentUserViewRepository extends BaseEloquentRepository implements UserViewRepository, RepositoryInterface
{
    protected $columns = array(
        'merchant_id',
        'user_id',
        'user_agent',
        'nonmember_id',
        'location_id',
        'franchise_id',
        'tracking_id',
        'url',
        'refer_id',
    );

    protected $model = 'UserView';

}
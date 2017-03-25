<?php namespace SOE\Repositories\Eloquent;

class UserLinkClickRepository extends BaseRepository implements \UserLinkClickRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'user_id',
        'nonmember_id',
        'location_id',
        'merchant_id',
        'franchise_id',
        'url',
        'type'
    );

    protected $model = 'UserLinkClick';

}
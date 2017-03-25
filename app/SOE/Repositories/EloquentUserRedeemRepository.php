<?php

class EloquentUserRedeemRepository extends BaseEloquentRepository implements UserRedeemRepository, RepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'code',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
    );

    protected $model = 'UserRedeem';

}
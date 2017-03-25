<?php

class EloquentUserPrintRepository extends BaseEloquentRepository implements UserPrintRepository, RepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'code',
        'type',
        'entity_id',
        'location_id',
        'merchant_id',
        'is_redemption',
        'latitude',
        'longitude',
        'latm',
        'lngm',
        'tracking_id',
        'url',
        'refer_id',
    );

    protected $model = 'UserPrint';

}
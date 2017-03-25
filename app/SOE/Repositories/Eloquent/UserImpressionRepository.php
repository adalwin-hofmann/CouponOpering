<?php namespace SOE\Repositories\Eloquent;

class UserImpressionRepository extends BaseRepository implements \UserImpressionRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'entity_id',
        'location_id',
        'merchant_id',
        'used_vehicle_id',
        'vehicle_style_id',
        'vehicle_model_id',
        'vehicle_entity_id',
    );

    protected $model = 'UserImpression';
}
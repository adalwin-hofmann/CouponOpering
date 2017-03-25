<?php

class EloquentUserImpressionRepository extends BaseEloquentRepository implements UserImpressionRepository, RepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'entity_id',
        'location_id',
        'merchant_id',
    );

    protected $model = 'UserImpression';

}
<?php

class EloquentUserClippedRepository extends BaseEloquentRepository implements UserClippedRepository, RepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'code',
    );

    protected $model = 'UserClipped';

}
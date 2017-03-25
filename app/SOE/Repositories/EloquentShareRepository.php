<?php

class EloquentShareRepository extends BaseEloquentRepository implements ShareRepository, RepositoryInterface
{
    protected $columns = array(
        'shareable_id',
        'shareable_type',
        'user_id',
        'type',
    );

    protected $model = 'Share';

}
<?php namespace SOE\Repositories\Eloquent;

class ShareEmailRepository extends BaseRepository implements \ShareEmailRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'shareable_id',
        'shareable_type',
        'share_id',
        'share_email',
    );

    protected $model = 'ShareEmail';

}


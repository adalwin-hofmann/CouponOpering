<?php namespace SOE\Repositories\Eloquent;

class NoteRepository extends BaseRepository implements \NoteRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'notable_id',
        'notable_type',
        'title',
        'content',
    );

    protected $model = 'Note';

}
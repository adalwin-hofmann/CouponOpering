<?php namespace SOE\Repositories\Eloquent;

class TrackedCallRepository extends BaseRepository implements \TrackedCallRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'call_sid',
        'account_sid',
        'call_from',
        'call_to',
        'direction',
        'from_city',
        'from_state',
        'from_zip',
        'from_country',
        'to_city',
        'to_state',
        'to_zip',
        'to_country',
        'dialcall_sid',
        'dialcall_duration',
        'dialcall_status',
        'recording_url',
    );

    protected $model = 'TrackedCall';
}
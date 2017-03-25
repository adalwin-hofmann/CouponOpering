<?php namespace SOE\Repositories\Eloquent;

class CompanyEventAttendeeRepository extends BaseRepository implements \CompanyEventAttendeeRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'company',
        'email',
        'company_event_id',
    );

    protected $model = 'CompanyEventAttendee';

    public function getByEventId($event_id)
    {
        return $this->query()
        	->where('company_event_id', '=', $event_id)
            ->get();
    }
}
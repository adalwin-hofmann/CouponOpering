<?php namespace SOE\Repositories\Eloquent;

class EventRepository extends BaseRepository implements \EventRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'merchant_id',
        'path',
        'description',
        'event_start',
        'event_end',
        'website',
        'starts_at',
        'expires_at',
        'is_demo',
        'is_active',
        'is_featured',
        'franchise_id',
        'is_location_specific',
        'short_name_line1',
        'short_name_line2',
        'custom_category_id',
        'custom_subcategory_id',
        'category_visible',
    );

    protected $model = 'Event';

    public function getByFranchiseId($franchise_id, $params = array(), $page = 0, $limit = 0)
    {
        $query = $this->query()
            ->where('franchise_id', $franchise_id)
            ->orderBy('name', 'asc');
        if(isset($params['filter']))
        {
            $query->where('name', 'LIKE', '%'.$params['filter'].'%');
        }
        if(isset($params['is_active']) && $params['is_active'] != -1)
        {
            $query->where('is_active', '=', $params['is_active']);
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        $query = $this->paginator($query, $limit, $page);
        $results = $query->get();
        $return = array('objects' => $results);
        $stats['stats']['returned'] = count($results);

        return array_merge($return, $stats);
    }

}
<?php namespace SOE\Repositories\Eloquent;

class ShareRepository extends BaseRepository implements \ShareRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'shareable_id',
        'shareable_type',
        'user_id',
        'type',
    );

    protected $model = 'Share';

    public function getShareStats($type = null, $start = null, $end = null, $market = null)
    {
        $query = $this->query();
        if($type)
            $query->where('shares.shareable_type', $type);
        if($start)
            $query->where('shares.created_at', '>=', $start);
        if($end)
            $query->where('shares.created_at', '<=', $end);
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query->join('users', 'shares.user_id', '=', 'users.id')
                    ->where('users.state', $abbr);
            }
        }
        return $query->remember(60*12)->count();
    }
}


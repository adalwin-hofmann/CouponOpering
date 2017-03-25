<?php namespace SOE\Repositories\Eloquent;

class UserPrintRepository extends BaseRepository implements \UserPrintRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'offer_id',
        'user_id',
        'nonmember_id',
        'code',
        'type',
        'entity_id',
        'location_id',
        'merchant_id',
        'is_redemption',
        'latitude',
        'longitude',
        'lngm',
        'latm',
        'tracking_id',
        'url',
        'refer_id',
    );

    protected $model = 'UserPrint';

    public function getRangePrints($start = null, $end = null, $type = 'all', $market = null)
    {
        $query = $this->query();
        if($start)
            $query->where('user_prints.created_at', '>=', $start);
        if($end)
            $query->where('user_prints.created_at', '<=', $end);
        switch ($type)
        {
            case 'prints':
                $query->where('user_prints.is_redemption', '0');
                break;
            case 'redemptions':
                $query->where('user_prints.is_redemption', '1');
                break;
        }
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query->join('locations', 'user_prints.location_id', '=', 'locations.id')
                    ->where('locations.state', $abbr);
            }
        }
        return $query->count();
    }

    public function getRepeatRangePrints($start = null, $end = null, $type = 'all', $market = null)
    {
        $query = $this->query();
        if($start)
            $query->where('user_prints.created_at', '>=', $start);
        if($end)
            $query->where('user_prints.created_at', '<=', $end);
        switch ($type)
        {
            case 'prints':
                $query->where('user_prints.is_redemption', '0');
                break;
            case 'redemptions':
                $query->where('user_prints.is_redemption', '1');
                break;
        }
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query->join('locations', 'user_prints.location_id', '=', 'locations.id')
                    ->where('locations.state', $abbr);
            }
        }

        $query->having('total', '>', '1');
        $query->groupBy('user_prints.user_id');
        $query->groupBy('user_prints.nonmember_id');
        $repeaters = $query->get(array(\DB::raw('COUNT(*) as total')));
        $total = 0;
        foreach($repeaters as $repeater)
        {
            $total += $repeater->total;
        }

        return array('total' => $total, 'average' => count($repeaters) ? $total / count($repeaters) : 0);
    }

    public function getRepeatRangePrinters($start = null, $end = null, $type = 'all')
    {
        $query = $this->query();
        if($start)
            $query->where('created_at', '>=', $start);
        if($end)
            $query->where('created_at', '<=', $end);
        switch ($type)
        {
            case 'prints':
                $query->where('is_redemption', '0');
                break;
            case 'redemptions':
                $query->where('is_redemption', '1');
                break;
        }

        $query->having('total', '>', '1');
        $query->groupBy('user_id');
        $query->groupBy('nonmember_id');
        $repeaters = $query->get(array(\DB::raw('COUNT(*) as total')));

        return count($repeaters);
    }
}
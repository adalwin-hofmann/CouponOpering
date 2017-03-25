<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Franchise extends Eloquent
{
    protected $softDelete = true;

    public static function boot()
    {
        parent::boot();

        Franchise::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * Franchise can have many notes.
     */
    public function notes()
    {
        return $this->morphMany('\SOE\DB\Note', 'notable');
    }

    /**
     * Franchise belongs to a single merchant.
     */
    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }
    
    public function assignments()
    {
        return $this->belongsToMany('\SOE\DB\User', 'franchise_assignments', 'franchise_id', 'user_id');
    }

    // scope
    public function scopeByRep($query, $user) {
        return $query
            ->join('franchise_assignments', 'franchises.id', '=', 'franchise_assignments.franchise_id')
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->where('franchise_assignments.user_id', '=', $user->id)
            ->orderBy('merchants.display');
    }

    public function scopeByAssignmentType($query, $type)
    {
        return $query
            ->join('user_assignment_types',
                'user_assignment_types.assignment_type_id', '=', 'assignment_types.id')
            ->join('franchise_assignments',
                'franchise_assignments.user_id' , '=', 'user_assignment_types.user_id')
            ->join('users', 'users.id', '=', 'franchise_assignments.user_id')
            ->join('franchises', 'franchises.id', '=', 'franchise_assignments.franchise_id')
            ->where('assignment_types.name', '=', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('franchises.is_active', '=', '1');
    }

    public function scopeMerchants($query)
    {
        return $query
            ->join('merchants', 'merchants.id', '=', 'franchises.merchant_id');
    }

    public function scopeLocations($query)
    {
        return $query
            ->leftJoin('locations', 'locations.franchise_id', '=', 'franchises.id');
    }

    public function scopeGetByName($query, $name)
    {
        return $query->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                    ->where('merchants.name', 'LIKE', '%'.str_replace("'", "", $name).'%');
    }

    public function scopeGetsLeads($query)
    {
        return $query->whereNotNull('franchises.netlms_id');
    }

    // Aggregate Scoping Functions (only use one at a time)

    public function scopeAggregateContests($query, $select)
    {
        $select[] =  \DB::raw('COUNT(contest_applications.contest_id) as applicants');
        $query
            ->select($select)
            ->leftJoin('contests', 'contests.franchise_id', '=', 'franchises.id')
            ->leftJoin('contest_applications',
                'contests.id', '=', 'contest_applications.contest_id');
    }

    public function scopeAggregateAllOffers($query, $select)
    {
        $select[] = \DB::raw('COUNT(offers.id) as all_offers');
        return $query
            ->select($select)
            ->leftJoin('offers', 'offers.franchise_id', '=', 'franchises.id');
    }

    /**
     * Helper function for aggregate offer data
     */
    private function getActiveOffers($query, $select)
    {
        return $query
            ->select($select)
            ->leftJoin('offers', 'offers.franchise_id', '=', 'franchises.id')
            ->where('offers.is_active', '=', 1)
            ->where('offers.expires_at', '>', \DB::raw('NOW()'));
    }

    public function scopeAggregateActiveOffers($query, $select)
    {
        $select[] = \DB::raw('COUNT(offers.id) as active_offers');
        return $this->getActiveOffers($query, $select);
    }

    public function scopeAggregateExpiringOffers($query, $select, $days = 10)
    {
        $select[] = \DB::raw('COUNT(offers.id) as expiring_offers');
        return $this->getActiveOffers($query, $select)
            ->where('offers.expires_at', '<',
                \DB::raw("DATE_ADD(NOW(), INTERVAL $days DAY)"));
    }
}


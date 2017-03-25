<?php namespace SOE\Repositories\Eloquent;

class ContestApplicationRepository extends BaseRepository implements \ContestApplicationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'user_id',
        'nonmember_id',
        'name',
        'city',
        'state',
        'zip',
        'phone',
        'address',
        'email',
        'allow_emails',
    );

    protected $model = 'ContestApplication';
    protected $contestRepository;

    public function __construct(
        \ContestRepositoryInterface $contestRepository
    )
    {
        $this->contestRepository = $contestRepository;
        parent::__construct();
    }

    /**
     * Select a group of semi-finalists from the application pool.
     *
     * @param integer $contest_id
     * @param integer $total
     * @return array
     */
    public function selectSemifinalists($contest_id, $total = 1)
    {
        return $this->query()
            ->leftJoin('contest_winners', function($join)
            {
                $join->on('contest_applications.contest_id', '=', 'contest_winners.contest_id')
                    ->on('contest_applications.user_id', '=', 'contest_winners.user_id');
            })
            ->where('contest_applications.contest_id', $contest_id)
            ->whereNull('contest_winners.verify_by')
            ->groupBy('contest_applications.user_id')
            ->orderBy(\DB::raw('RAND()'))
            ->take($total)
            ->get(array('contest_applications.*'));
    }

    /**
     * Get all remaining applicants to as semifinalists.
     *
     * @param integer $contest_id
     * @return array
     */
    public function makeAllSemifinalists($contest_id)
    {
        return $this->query()
            ->leftJoin('contest_winners', function($join)
            {
                $join->on('contest_applications.contest_id', '=', 'contest_winners.contest_id')
                    ->on('contest_applications.user_id', '=', 'contest_winners.user_id');
            })
            ->where('contest_applications.contest_id', $contest_id)
            ->whereNull('contest_winners.verify_by')
            ->groupBy('contest_applications.user_id')
            ->get(array('contest_applications.*'));
    }

    /**
     * Get all remaining applicants to as semifinalists even if they have already been notified.
     *
     * @param integer $contest_id
     * @return array
     */
    public function getAllSemifinalists($contest_id)
    {
        return $this->query()
            ->leftJoin('contest_winners', function($join)
            {
                $join->on('contest_applications.contest_id', '=', 'contest_winners.contest_id')
                    ->on('contest_applications.user_id', '=', 'contest_winners.user_id');
            })
            ->where('contest_applications.contest_id', $contest_id)
            ->whereNull('contest_winners.verified_at')
            ->groupBy('contest_applications.user_id')
            ->get(array('contest_applications.*'));
    }    
}
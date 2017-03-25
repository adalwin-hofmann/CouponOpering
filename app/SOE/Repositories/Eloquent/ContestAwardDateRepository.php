<?php namespace SOE\Repositories\Eloquent;

class ContestAwardDateRepository extends BaseRepository implements \ContestAwardDateRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'award_at',
        'all_awarded_at',
        'verify_by',
        'verify_attempts',
        'winners',
        'has_prize',
        'prize_name',
        'prize_description',
        'prize_expiration_date',
        'prize_authorizer',
        'prize_authorizer_title',
        'redeemable_at'
    );

    protected $model = 'ContestAwardDate';
    protected $contestRepository;

    public function __construct(
        \ContestRepositoryInterface $contestRepository
    )
    {
        $this->contestRepository = $contestRepository;
        parent::__construct();
    }

    public function getReadyForAward()
    {
        $sql = <<<SQL
select `contest_award_dates`.*, Coalesce(`total_winners`.`winners`, 0) as award_winners from `contest_award_dates` 
    left join (
        select count(*) as winners, award_date_id from contest_winners 
            where verified_at IS NOT NULL 
            group by award_date_id
    ) total_winners on `contest_award_dates`.`id` = `total_winners`.`award_date_id` 
    where `award_at` <= ? 
        and `contest_award_dates`.`all_awarded_at` is null
        and (`contest_award_dates`.`verify_by` is null
            or `contest_award_dates`.`verify_by` < ?
            )
        and (`total_winners`.`winners` is null 
            or `total_winners`.`winners` < `contest_award_dates`.`winners`
            )
SQL;
        $dates = \DB::select($sql, [date('Y-m-d H:i:s', strtotime('-3 hours')), date('Y-m-d H:i:s', strtotime('-3 hours'))]);
        $aIDs = array(0);
        foreach($dates as $date)
        {
            $aIDs[] = $date->id;
        }
        return $this->query()->whereIn('id', $aIDs)
            ->leftJoin(\DB::raw(
                '(select count(*) as winners, award_date_id from contest_winners where verified_at IS NOT NULL group by award_date_id) total_winners'
            ), 'contest_award_dates.id', '=', \DB::raw('total_winners.award_date_id'))
            ->get(array(
                'contest_award_dates.*',
                \DB::raw('Coalesce(`total_winners`.`winners`, 0) as award_winners')
            ));
    }

    /**
     * Determine if a contest award date is open for winner selection.
     *
     * @param integer $id Award date id.
     * @return boolean
     */
    public function isOpenForAwarding($id)
    {
        $sql = <<<SQL
select `contest_award_dates`.*, Coalesce(`total_winners`.`winners`, 0) as award_winners from `contest_award_dates` 
    left join (
        select count(*) as winners, award_date_id from contest_winners 
            where verified_at IS NOT NULL 
            group by award_date_id
    ) total_winners on `contest_award_dates`.`id` = `total_winners`.`award_date_id` 
    where `award_at` <= ? 
        and `contest_award_dates`.`all_awarded_at` is null
        and `contest_award_dates`.`id` = ?
        and (`total_winners`.`winners` is null 
            or `total_winners`.`winners` < `contest_award_dates`.`winners`
            )
SQL;
        $dates = \DB::select($sql, [date('Y-m-d H:i:s'), $id]);
        
        return count($dates) >= 1;
    }

    /**
     * Get the number of selected winners.
     *
     * @param integer $id
     * @return integer
     */
    public function selectedWinners($id)
    {
        $sql = <<<SQL
select `contest_award_dates`.*, Coalesce(`total_winners`.`winners`, 0) as award_winners from `contest_award_dates` 
    left join (
        select count(*) as winners, award_date_id from contest_winners 
            where verified_at IS NOT NULL 
            group by award_date_id
    ) total_winners on `contest_award_dates`.`id` = `total_winners`.`award_date_id` 
    where `contest_award_dates`.`id` = ?
SQL;
        $dates = \DB::select($sql, [$id]);
        return $dates[0]->award_winners;
    }

    public function getByContest($contest_id)
    {
        return $this->query()
            ->where('contest_id', $contest_id)
            ->orderBy('award_at')
            ->get();
    }

    public function updateDate($id, $params = array())
    {
        return $this->query()
            ->where('id', $id)
            ->update(array(
                'award_at' => $params['award_at'],
                'winners' => $params['winners'],
                'has_prize' => isset($params['has_prize']) ? $params['has_prize'] : 0,
                'prize_name' => isset($params['prize_name']) ? $params['prize_name'] : '',
                'redeemable_at' => isset($params['redeemable_at']) ? $params['redeemable_at'] : '',
                'prize_description' => isset($params['prize_description']) ? $params['prize_description'] : '',
                'prize_expiration_date' => isset($params['prize_expiration_date']) ? $params['prize_expiration_date'] : '',
                'prize_authorizer' => isset($params['prize_authorizer']) ? $params['prize_authorizer'] : '',
                'prize_authorizer_title' => isset($params['prize_authorizer_title']) ? $params['prize_authorizer_title'] : '',
            ));
    }

    public function deleteDate($id)
    {
        return $this->query()
            ->where('id', $id)
            ->delete();
    }

    public function copyDate($id)
    {
        $date = $this->find($id);
        if($date)
        {
            $this->create(array(
                'contest_id' => $date->contest_id,
                'award_at' => $date->award_at,
                'winners' => $date->winners,
                'has_prize' => $date->has_prize,
                'prize_company' => $date->prize_company,
                'redeemable_at' => $date->redeemable_at,
                'prize_description' => $date->prize_description,
                'prize_expiration_date' => $date->prize_expiration_date,
                'prize_authorizer' => $date->prize_authorizer,
                'prize_authorizer_title' => $date->prize_authorizer_title,
            ));
        }
    }
}
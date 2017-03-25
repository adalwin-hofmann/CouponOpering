<?php namespace SOE\Repositories\Eloquent;

class ContestWinnerRepository extends BaseRepository implements \ContestWinnerRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'user_id',
        'first_name',
        'last_name',
        'city',
        'state',
        'selected_at',
        'verify_by',
        'verified_at',
        'award_date_id',
        'verify_key',
        'redeemed_at',
        'email',
        'address',
        'zip'
    );

    protected $model = 'ContestWinner';
    protected $contestRepository;

    public function __construct(
        \ContestRepositoryInterface $contestRepository,
        \ContestAwardDateRepositoryInterface $contestAwardDates,
        \ContestDisclaimerRepositoryInterface $contestDisclaimers,
        \UserRepositoryInterface $users
    )
    {
        $this->contestRepository = $contestRepository;
        $this->contestAwardDates = $contestAwardDates;
        $this->contestDisclaimers = $contestDisclaimers;
        $this->users = $users;
        parent::__construct();
    }

    /**
     * Assign the given user as the winner of the given contest.
     *
     * @param SOE\DB\User $user
     * @param SOE\DB\Contest $contest
     * @param string $city
     * @param string $state
     */
    public function assignWinner(\SOE\DB\User $user, \SOE\DB\Contest $contest, $city = '', $state = '')
    {
        $winner = new \SOE\DB\ContestWinner;
        $winner->contest_id = $contest->id;
        $winner->user_id = $user->id;
        $aNames = explode(' ', $user->name);
        $winner->first_name = isset($aNames[0]) ? $aNames[0] : '';
        $winner->last_name = isset($aNames[1]) ? $aNames[1] : '';
        $winner->city = empty($city) ? $user->city : $city;
        $winner->state = empty($state) ? $user->state : $state;
        $winner->verified_at = \DB::raw('NOW()');
        $winner->save();
        return $winner;
    }

    public function delete($winnerID)
    {
        $winner = \SOE\DB\ContestWinner::where('id','=',$winnerID)->first();
        $winner->delete();
        return $winner;
    }

    public function findByVerifyKey($verify_key)
    {

        $winner = $this->query()
            ->where('verify_key', $verify_key)
            ->where('verify_by', '>=', date('Y-m-d H:i:s'))
            ->whereNull('verified_at')
            ->first();

        if(!$winner)
            return false;

        $open = $this->contestAwardDates->isOpenForAwarding($winner->award_date_id);

        return $open ? $winner : false;
    }

    /**
     * Get a verified winner by his verify key.
     *
     * @param string $verify_key
     * @return mixed
     */
    public function getWinnerByKey($verify_key)
    {
        return $this->query()
            ->where('verify_key', $verify_key)
            ->whereNotNull('verified_at')
            ->first();
    }

    public function redeemPrize($id)
    {
        return $this->query()
            ->where('id', $id)
            ->whereNull('redeemed_at')
            ->update(array(
                'redeemed_at' => date('Y-m-d H:i:s')
            ));
    }

    public function verified($id)
    {
        $winner = $this->find($id);
        $winner->verified_at = date('Y-m-d H:i:s');
        $winner->save();
        $date = $this->contestAwardDates->find($winner->award_date_id);
        $selected = $this->contestAwardDates->selectedWinners($winner->award_date_id);
        $contest = $this->contestRepository->find($winner->contest_id);
        $user = $this->users->find($winner->user_id);
        if($contest->is_automated)
        {
            \Mail::send('emails.contest-prize', ['contest' => $contest->toArray(), 'winner' => $winner->toArray()], function($message) use ($user)
            {
                $message->subject('SaveOn - Contest Prize')->to($user->email, '');
            });
        } else {
            \Mail::send('emails.contest-prize-mail', ['contest' => $contest->toArray(), 'winner' => $winner->toArray()], function($message) use ($user)
            {
                $message->subject('SaveOn - Contest Prize')->to($user->email, '');
            });
        }

        if($selected >= $date->winners)
        {
            $date->all_awarded_at = date('Y-m-d H:i:s');
            $date->save();
        }
    }

    public function getByContestAndUser($user_id, $contest_id)
    {
        return $this->query()
            ->where('user_id', $user_id)
            ->where('contest_id', $contest_id)
            ->first();
    }

}
<?php namespace SOE\Repositories\Eloquent;

class ContestDisclaimerRepository extends BaseRepository implements \ContestDisclaimerRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'contest_award_date_id',
        'name',
        'check_1',
        'check_2',
        'check_3',
        'check_4',
        'check_5',
        'check_6',
        'verified_at',
        'winner_name',
        'birth_date',
        'address',
        'city_state_zip',
        'daytime_phone',
        'evening_phone',
        'email',
        'contest_winner_id'
    );

    protected $model = 'ContestDisclaimer';

    public function __construct()
    {
        parent::__construct();
    }
}
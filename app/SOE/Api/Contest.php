<?php
namespace SOE\Api;

class Contest extends Api implements ApiInterface, ContestApi
{
    public function __construct(
        \ContestRepositoryInterface $repository,
        \ContestWinnerRepositoryInterface $contestWinnerRepository
    )
    {
        $this->repository = $repository;
        $this->contestWinnerRepository = $contestWinnerRepository;
    }

    public function find()
    {
        $input = \Input::get();

        if (!array_key_exists('limit', $input)) {
            $input['limit'] = self::DEFAULT_LIMIT;
        }

        if (!array_key_exists('page', $input)) {
            $input['page'] = self::DEFAULT_PAGE;
        }

        $filter = array();

        if (array_key_exists('name', $input)) {
            $name = strtolower($input['name']);

            $filter[] = array(
                'type' => 'orWhere',
                'key' => 'name',
                'operator' => 'like',
                'value' => "%$name%"
            );
            $filter[] = array(
                'type' => 'orWhere',
                'key' => 'contest_description',
                'operator' => 'like',
                'value' => "%$name%"
            );
            $filter[] = array(
                'type' => 'orWhere',
                'key' => 'display_name',
                'operator' => 'like',
                'value' => "%$name%"
            );
        }

        $result = $this->repository->format(
            $this->repository->get(
                $filter,
                $input['limit'],
                $input['page']
            )
        );
        //var_dump(\DB::getQueryLog());
        return $result;
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function getNearbyOpen()
    {
        return $this->format($this->repository->getNearbyOpen(\Input::get('user_id'), \Input::get('lat'), \Input::get('lng'), \Input::get('page', 0), \Input::get('limit', 0)));
    }

    public function getApplicantDetails()
    {
        $details = $this->repository->getApplicantDetails(\Input::get('id'));
        return json_encode($details);
    }

    public function getApplicantUsers()
    {
        return $this->format($this->repository->getApplicantUsers(\Input::get('contest_id'), \Input::get('name', null), \Input::get('start', null), \Input::get('end', null), \Input::get('page', 0), \Input::get('limit', 0)));
    }

    public function getWinners()
    {
        return $this->format($this->repository->getWinners(\Input::get('contest_id')));
    }

    public function getAllWinners()
    {
        $userSpecific = \Input::get('userSpecific', false);
        $contestSearch = urldecode(\Input::get('contest', null));
        $contestMerchantSearch = urldecode(\Input::get('merchant', null));
        return $this->format($this->repository->getAllWinners($userSpecific, $contestSearch, $contestMerchantSearch, \Input::get('orderBy', null), \Input::get('orderByOrder', null)));
    }

    public function getAllWinnersDetail()
    {
        $contestSearch = urldecode(\Input::get('contest', null));
        $contestMerchantSearch = urldecode(\Input::get('merchant', null));
        $contestEmailSearch = urldecode(\Input::get('email', null));
        $contestLastNameSearch = urldecode(\Input::get('lastname', null));
        return $this->format($this->repository->getAllWinnersDetail($contestSearch, $contestMerchantSearch, $contestEmailSearch, $contestLastNameSearch,  \Input::get('orderBy', null), \Input::get('orderByOrder', null)));
    }

    public function getWinnerInfo()
    {
        return $this->format($this->repository->getWinnerInfo(\Input::get('winner_id')));
    }

    public function sendEndingEmail()
    {
        return $this->format($this->repository->sendEndingEmail(\Input::get('contest_id'), \Input::get('test_email', null)));
    }

    public function redeemPrize()
    {
        return $this->format($this->contestWinnerRepository->redeemPrize(\Input::get('winner_id')));
    }

    public function statReport()
    {
        $franchise_id = \Input::get('franchise_id');
        $location_id = \Input::get('location_id', 0);
        return $this->format($this->repository->statReport($franchise_id, $location_id));
    }
}

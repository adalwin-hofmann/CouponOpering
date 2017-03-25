<?php
namespace SOE\Api;

class ContestWinner extends Api implements ApiInterface, ContestWinnerApi
{
    public function __construct(
        \ContestWinnerRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    { 
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->delete(\Input::get('contest_winner_id')));
    }

    public function update()
    {
    }

    public function getWinners()
    {
        //return $this->format($this->repository->getWinners(\Input::get('contest_id')));
    }
}

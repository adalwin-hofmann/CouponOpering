<?php
namespace SOE\Api;

class ContestAwardDate extends Api implements ApiInterface, ContestAwardDateApi
{
    public function __construct(
        \ContestAwardDateRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id', 0)));
    }

    public function create()
    {
        return $this->format($this->repository->create(\Input::all()));
    }

    public function get()
    {
    }

    public function update()
    {
        return $this->format($this->repository->updateDate(\Input::get('award_date_id'), \Input::all()));
    }

    public function delete()
    {
        return $this->format($this->repository->deleteDate(\Input::get('award_date_id')));
    }

    public function getByContest()
    {
        return $this->format($this->repository->getByContest(\Input::get('contest_id', 0)));
    }

    public function copy()
    {
        return $this->format($this->repository->copyDate(\Input::get('award_date_id')));
    }

}

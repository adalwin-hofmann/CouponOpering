<?php
namespace SOE\Api;

class ContestLocation extends Api implements ApiInterface, ContestLocationApi
{
    public function __construct(
        \ContestLocationRepositoryInterface $repository,
        \ContestRepositoryInterface $contests
    )
    {
        $this->repository = $repository;
        $this->contests = $contests;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id', 0)));
    }

    public function create()
    {
        return $this->format($this->repository->addForContestByZipcode(
            $this->contests->find(\Input::get('contest_id')),
            \Input::get('zipcode'),
            \Input::get('service_radius')
        ));
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->removeForContestByZipcode(
            $this->contests->find(\Input::get('contest_id')),
            \Input::get('zipcode')
        ));
    }

    public function getForContest()
    {
        return $this->format($this->repository->getForContest($this->contests->find(\Input::get('contest_id'))));
    }

}

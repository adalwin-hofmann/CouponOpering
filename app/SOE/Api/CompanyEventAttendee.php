<?php
namespace SOE\Api;

class CompanyEventAttendee extends Api implements ApiInterface,CompanyEventAttendeeApi
{
    public function __construct(
        \CompanyEventAttendeeRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id')));
    }

    public function create()
    {
        //return $this->format($this->repository->createPage(\Input::all()));
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
        //return $this->format($this->repository->destroy(\Input::get('id')));
    }

    public function getByEvent()
    {
        return $this->format($this->repository->getByEventId(\Input::get('event_id', 0)));
    }
}

<?php
namespace SOE\Api;

class CompanyEvent extends Api implements ApiInterface,CompanyEventApi
{
    public function __construct(
        \CompanyEventRepositoryInterface $repository
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
        $event_id = \Input::get('event_id', 0);
        if($event_id)
            return $this->format($this->repository->update($event_id, \Input::all()));
        else
            return $this->format($this->repository->create(\Input::all()));
    }

    public function get()
    {
        return $this->format($this->repository->getOrderDate());
    }

    public function update()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->destroy(\Input::get('id')));
    }

    public function search()
    {
        return $this->format($this->repository->searchByName(\Input::get('keyword')));
    }
}

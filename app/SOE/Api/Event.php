<?php
namespace SOE\Api;

class Event extends Api implements ApiInterface, EventApi
{
    public function __construct(
        \EventRepositoryInterface $repository
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

    public function update()
    {
    }

    public function getByFranchiseId()
    {
        return $this->format($this->repository->getByFranchiseId(\Input::get('franchise_id'), \Input::all(), \Input::get('page', 0), \Input::get('limit', 0)));
    }
}

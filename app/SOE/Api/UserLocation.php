<?php
namespace SOE\Api;

class UserLocation extends Api implements ApiInterface, UserLocationApi
{
    public function __construct(
        \UserLocationRepositoryInterface $repository
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

    public function saveLocation()
    {
        return $this->format($this->repository->saveLocation(\Input::get('user_id'), \Input::get('latitude', null), \Input::get('longitude', null)));
    }

}

<?php
namespace SOE\Api;

class Feature extends Api implements ApiInterface, FeatureApi
{
    public function __construct(
        \FeatureRepositoryInterface $repository
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

    public function updateByName()
    {
        return $this->format($this->repository->updateByName(\Input::get('name'), \Input::all()));
    }

    public function findByName()
    {
        return $this->format($this->repository->findByName(\Input::get('name'), \Input::get('remember', 1)));
    }
}

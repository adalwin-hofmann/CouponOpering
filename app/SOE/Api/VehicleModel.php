<?php
namespace SOE\Api;

class VehicleModel extends Api implements ApiInterface, VehicleModelApi
{
    public function __construct(
        \VehicleModelRepositoryInterface $repository
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

    public function getByMake()
    {
        return $this->format($this->repository->getByMake(\Input::get('make')));
    }

    public function getByMakeId()
    {
        return $this->format($this->repository->getByMakeId(\Input::get('make_id')));
    }

    public function getNewByMake()
    {
        return $this->format($this->repository->getNewByMake(\Input::get('make')));   
    }
}

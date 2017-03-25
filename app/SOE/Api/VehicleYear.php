<?php
namespace SOE\Api;

class VehicleYear extends Api implements ApiInterface, VehicleYearApi
{
    public function __construct(
        \VehicleYearRepositoryInterface $repository
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

    public function getNewByMake()
    {
        return $this->format($this->repository->getNewByMake(\Input::get('make')));
    }
}

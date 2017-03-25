<?php
namespace SOE\Api;

class VehicleIncentive extends Api implements ApiInterface, VehicleIncentiveApi
{
    public function __construct(
        \VehicleIncentiveRepositoryInterface $repository
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
    }

    public function get()
    {
    }

    public function update()
    {
    }
}

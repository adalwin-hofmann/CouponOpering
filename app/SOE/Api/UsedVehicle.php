<?php
namespace SOE\Api;

class UsedVehicle extends Api implements ApiInterface, UsedVehicleApi
{
    public function __construct(
        \UsedVehicleRepositoryInterface $usedVehicleRepository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->repository = $usedVehicleRepository;
        $this->userRepository = $userRepository;
    }

    public function find()
    {
        return $this->format($this->repository->with('merchant')->where('id', \Input::get('id'))->first());
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

    public function getByMerchant()
    {
        return $this->format($this->repository->getByMerchant(
            \Input::get('merchant_id'),
            \Input::get('page', 0),
            \Input::get('limit', 12)
        ));
    }

    public function search()
    {
        return $this->format($this->repository->search(
            \Input::get('year', null), 
            \Input::get('make', null), 
            \Input::get('model', null), 
            \Input::get('min', null), 
            \Input::get('max', null), 
            \Input::get('dist', null),
            \Input::get('page', 0),
            \Input::get('limit', 0),
            \Input::get('order', 'dist')));
    }

    public function searchRelated()
    {
        return $this->format($this->repository->searchRelated(
            \Input::get('year', null), 
            \Input::get('make', null), 
            \Input::get('model', null), 
            \Input::get('min', null), 
            \Input::get('max', null), 
            \Input::get('dist', null),
            \Input::get('page', 0),
            \Input::get('limit', 0)));
    }
}

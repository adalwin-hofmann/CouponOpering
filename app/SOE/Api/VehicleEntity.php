<?php
namespace SOE\Api;

class VehicleEntity extends Api implements ApiInterface, VehicleEntityApi
{
    public function __construct(
        \VehicleEntityRepositoryInterface $vehicleEntityRepository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->repository = $vehicleEntityRepository;
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
            \Input::get('order', 'rand'),
            \Input::get('mileage_limit', null),
            \Input::get('body_style', null),
            \Input::get('state', null),
            \Input::get('with_stats', true)));
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

    public function brokenVehicleImage()
    {
        return $this->format($this->repository->brokenVehicleImage(
            \Input::get('image'),
            \Input::get('vehicle_id')
        ));
    }
}

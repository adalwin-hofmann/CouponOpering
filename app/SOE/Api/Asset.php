<?php
namespace SOE\Api;

class Asset extends Api implements ApiInterface, AssetApi
{
    public function __construct(
        \AssetRepositoryInterface $repository,
        \LocationRepositoryInterface $locations
    )
    {
        $this->repository = $repository;
        $this->locations = $locations;
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

    public function delete()
    {
    }

    public function update()
    {
    }

    public function getLocationLogo()
    {
        $location = $this->locations->find(\Input::get('location_id'));
        return $this->format($this->repository->getLocationLogo($location, false));
    }
    public function getLocationBanner()
    {
        $location = $this->locations->find(\Input::get('location_id'));
        return $this->format($this->repository->getLocationBanner($location, false));
    }
}

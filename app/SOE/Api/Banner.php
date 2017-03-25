<?php
namespace SOE\Api;

class Banner extends Api implements ApiInterface, BannerApi
{
    public function __construct(
        \BannerRepositoryInterface $repository
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

    public function delete()
    {
    }

    public function update()
    {
    }

    public function getByFranchise()
    {
        return $this->format($this->repository->getByFranchise(\Input::get('franchise_id'), \Input::get('show_inactive', 0)));
    }
}

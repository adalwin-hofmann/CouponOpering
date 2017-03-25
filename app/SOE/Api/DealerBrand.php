<?php
namespace SOE\Api;

class DealerBrand extends Api implements ApiInterface, DealerBrandApi
{
    public function __construct(
        \DealerBrandRepositoryInterface $repository
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

    public function getByMerchant()
    {
        return $this->format($this->repository->getByMerchant(
            \Input::get('merchant_id'),
            \Input::get('page', 0),
            \Input::get('limit', 0)));
    }
}

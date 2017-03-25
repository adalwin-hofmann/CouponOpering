<?php
namespace SOE\Api;

class Merchant extends Api implements ApiInterface, MerchantApi
{
    public function __construct(
        \MerchantRepositoryInterface $repository
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

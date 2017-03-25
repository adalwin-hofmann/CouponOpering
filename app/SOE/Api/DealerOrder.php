<?php
namespace SOE\Api;

class DealerOrder extends Api implements ApiInterface, DealerOrderApi
{
    public function __construct(
        \DealerOrderRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    { 
    }

    public function create()
    {
        $order_id = \Input::get('order_id', 0);
        if($order_id)
            return $this->format($this->repository->update($order_id, \Input::all()));
        else
            return $this->format($this->repository->create(\Input::all()));
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->destroy(\Input::get('order_id')));
    }

    public function getByFranchise()
    {
        return $this->format($this->repository->getByFranchise(
            \Input::get('franchise_id'),
            \Input::get('page', 0),
            \Input::get('limit', 0)));
    }
}

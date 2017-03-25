<?php
namespace SOE\Api;

class Category extends Api implements ApiInterface, CategoryApi
{
    public function __construct(
        \CategoryRepositoryInterface $repository
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

    public function getByParentId()
    {
        return $this->format($this->repository->getByParentId(\Input::get('parent_id', 0)));
    }

}

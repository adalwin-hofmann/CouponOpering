<?php
namespace SOE\Api;

class AssetCategory extends Api implements ApiInterface, AssetCategoryApi
{
    public function __construct(
        \AssetCategoryRepositoryInterface $repository
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
        return $this->format($this->repository->getByParentId(\Input::get('parent_id', 0)));
    }

    public function delete()
    {
    }

    public function update()
    {
    }
}

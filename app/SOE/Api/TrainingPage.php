<?php
namespace SOE\Api;

class TrainingPage extends Api implements ApiInterface, TrainingPageApi
{
    public function __construct(
        \TrainingPageRepositoryInterface $repository
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
        return $this->format($this->repository->createPage(\Input::all()));
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->destroy(\Input::get('id')));
    }
}

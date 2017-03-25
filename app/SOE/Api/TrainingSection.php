<?php
namespace SOE\Api;

class TrainingSection extends Api implements ApiInterface, TrainingSectionApi
{
    public function __construct(
        \TrainingSectionRepositoryInterface $repository
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
        return $this->format($this->repository->createSection(\Input::all()));
    }

    public function get()
    {
        return $this->format($this->repository->listSections(\Input::get('page'), \Input::get('limit'), \Input::get('order'), \Input::get('roles')));
    }

    public function update()
    {
    }

    public function delete()
    {
        return $this->format($this->repository->destroy(\Input::get('id')));
    }

    public function getChildrenByParent()
    {
        return $this->format($this->repository->getChildrenByParent(\Auth::User(), \Input::get('parent_id')));
    }
}

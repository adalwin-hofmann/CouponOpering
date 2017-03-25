<?php
namespace SOE\Api;

class UserAssignmentType extends Api implements ApiInterface, UserAssignmentTypeApi
{
    public function __construct(
        \UserAssignmentTypeRepositoryInterface $repository
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

    public function getByUser()
    {
        return $this->format($this->repository->query()->where('user_id', \Input::get('user_id'))->get());
    }

}

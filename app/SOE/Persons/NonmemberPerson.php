<?php namespace SOE\Persons;

class NonmemberPerson extends Person implements PersonInterface
{
    public function __construct($nonmember_id = null)
    {
        parent::__construct();
        $this->model = $nonmember_id ? $this->repository->find($nonmember_id, false, true) : \Auth::nonmember();
    }

    public function getForeignKey()
    {
        return 'nonmember_id';
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function shouldTrack()
    {
        return $this->model->id ? true : false;
    }
}
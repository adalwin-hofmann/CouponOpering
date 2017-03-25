<?php namespace SOE\Persons;

class UserPerson extends Person implements PersonInterface
{
    public function __construct($user_id = null)
    {
        parent::__construct();
        $this->model = $user_id ? $this->repository->find($user_id, false, true) : \Auth::User();
    }

    public function getForeignKey()
    {
        return 'user_id';
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function shouldTrack()
    {
        if(!$this->exists())
            return false;
        $types = explode(',', $this->model->type);
        if((in_array('Employee', $types) || in_array('Demo', $types)))
        {
            return false;
        }
        return true;
    }
}
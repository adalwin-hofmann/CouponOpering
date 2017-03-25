<?php namespace SOE\Persons;

abstract class Person
{
    protected $model;
    protected $repository;

    /**
     * Automatically determine the repository at construction time
     */
    public function __construct() {
        $class = join('', array_slice(explode('\\', get_called_class()), -1));
        $repositoryName = substr($class, 0, -strlen('Person')).'RepositoryInterface';
        $this->repository = \App::make($repositoryName);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function exists()
    {
        return $this->model ? true : false;
    }
}
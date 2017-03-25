<?php namespace SOE\Clickables;

abstract class Clickable
{
    protected $model;
    protected $repository;

    /**
     * Automatically determine the repository at construction time
     */
    public function __construct($repository = null) {
        if(!$repository)
        {
            $class = join('', array_slice(explode('\\', get_called_class()), -1));
            $repositoryName = substr($class, 0, -strlen('Clickable')).'RepositoryInterface';
            $this->repository = \App::make($repositoryName);
        }
        else
        {
            $this->repository = $repository;
        }
    }

    public function getModel()
    {
        return $this->model;
    }
}
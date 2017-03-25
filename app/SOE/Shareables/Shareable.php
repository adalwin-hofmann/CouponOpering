<?php namespace SOE\Shareables;

abstract class Shareable
{
    protected $model;
    protected $repository;

    /**
     * Automatically determine the repository at construction time
     */
    public function __construct() {
        $class = join('', array_slice(explode('\\', get_called_class()), -1));
        $repositoryName = substr($class, 0, -strlen('Shareable')).'RepositoryInterface';
        $this->repository = \App::make($repositoryName);
    }
}
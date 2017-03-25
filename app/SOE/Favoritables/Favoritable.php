<?php namespace SOE\Favoritables;

abstract class Favoritable
{
    protected $model;
    protected $repository;

    /**
     * Automatically determine the repository at construction time
     */
    public function __construct() {
        $class = join('', array_slice(explode('\\', get_called_class()), -1));
        $repositoryName = substr($class, 0, -strlen('Favoritable')).'RepositoryInterface';
        $this->repository = \App::make($repositoryName);
    }
}
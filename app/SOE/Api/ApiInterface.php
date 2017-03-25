<?php
namespace SOE\Api;

interface ApiInterface
{
    //public function __construct(\RepositoryInterface $repository);
    public function create();
    public function find();
    public function get();
    public function update();
}

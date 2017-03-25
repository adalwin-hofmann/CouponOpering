<?php namespace SOE\Viewables;

interface ViewableInterface
{
    public function __construct($object_id);
    public function view(\SOE\Persons\PersonInterface $viewer);
}
<?php namespace SOE\Clickables;

interface ClickableInterface
{
    public function __construct($object_id);
    public function click(\SOE\Persons\PersonInterface $clicker);
}
<?php namespace SOE\Printables;

interface PrintableInterface
{
    public function __construct($object_id);
    public function printItem(\SOE\Persons\PersonInterface $printer);
}
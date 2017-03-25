<?php namespace SOE\Persons;

interface PersonInterface
{
    public function getModel();
    public function exists();
    public function getForeignKey();
    public function getId();
    public function shouldTrack();
}
<?php namespace SOE\Shareables;

interface ShareableInterface
{
    public function __construct($object_id);
    public function share(\SOE\Persons\PersonInterface $person, $type, $emails = '', $message = '', $from = '', $sharer = '');
}
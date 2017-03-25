<?php namespace SOE\Syndicators;

interface SyndicatorInterface
{
    public function getSyndicatedObjects($format, $params = array());
}
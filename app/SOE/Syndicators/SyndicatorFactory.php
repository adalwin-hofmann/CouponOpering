<?php namespace SOE\Syndicators;

class SyndicatorFactory
{
    public function build($type)
    {
        $type = 'SOE\Syndicators\\'.studly_case($type).'Syndicator';
        if(class_exists($type))
            return new $type;
        else
            throw new SyndicatorException('unknown syndicator type');    
    }
}
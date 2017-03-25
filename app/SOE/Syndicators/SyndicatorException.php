<?php namespace SOE\Syndicators;

class SyndicatorException extends \Exception
{
    public function __construct($message = '', $code = 400)
    {
        parent::__construct(empty($message) ? 'Syndicator Error' : $message, $code);
    }
};

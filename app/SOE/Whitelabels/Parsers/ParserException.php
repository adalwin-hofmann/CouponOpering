<?php namespace SOE\Whitelabels\Parsers;

class ParserException extends \Exception {

    public function __construct($message = '', $code = 400)
    {
        parent::__construct(empty($message) ? 'Parser Error' : $message, $code);
    }
};

<?php namespace SOE\Newsletters;

class NewsletterException extends \Exception {

    public function __construct($message = '', $code = 400)
    {
        parent::__construct(empty($message) ? 'Newsletter Error' : $message, $code);
    }
};

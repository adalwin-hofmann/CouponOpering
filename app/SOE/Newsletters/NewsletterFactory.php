<?php namespace SOE\Newsletters;

class NewsletterFactory
{
    public function __construct()
    {

    }

    public function make($type)
    {
        try
        {
            $type = studly_case($type);
            $newsletter = \App::make('\SOE\Newsletters\\'.$type.'Newsletter');
        }
        catch(\Exception $e) {
            throw new NewsletterException('unknown newsletter type');
        }

        return $newsletter;
    }
}
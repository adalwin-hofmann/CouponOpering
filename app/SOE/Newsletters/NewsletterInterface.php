<?php namespace SOE\Newsletters;

interface NewsletterInterface
{
    public function prep($schedule_id);
    public function send($schedule_id, $limit = 500);
}
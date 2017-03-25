<?php
namespace SOE\Api;

class NewsletterSchedule extends Api implements ApiInterface, NewsletterScheduleApi
{
    public function __construct(
        \NewsletterScheduleRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->format($this->repository->getLatest(\Input::get('type'), null, \Input::get('batch_id')));
    }

    public function create()
    {
    }

    public function get()
    {
        return $this->format($this->repository->getAll());
    }

    public function update()
    {
        $newsletter = $this->repository->updateLatest(\Input::get('schedule_id', 0), \Input::get('type'), \Input::get('batch_id'), \Input::all());
        if($_ENV['APP_MODE'] == 'Content' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="content"))
        {
            \Event::fire('backoffice.updated', array('newsletter_schedule', $newsletter->id, \Auth::user()->id, $newsletter, 'newsletter schedule updated'));
        }
        return $this->format($newsletter);
    }

    
}

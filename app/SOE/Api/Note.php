<?php
namespace SOE\Api;

class Note extends Api implements ApiInterface, NoteApi
{
    public function __construct(
        \NoteRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id')));
    }

    public function create()
    {
        $note = $this->repository->create(\Input::all());
        if($_ENV['APP_MODE'] == 'Content' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="content"))
        {
            \Event::fire('backoffice.updated', array('note', $note->id, \Auth::user()->id, $note, 'note created'));
        }
        return $this->format($note);
    }

    public function get()
    {
    }

    public function update()
    {
        $note = $this->repository->update(\Input::get('id'), \Input::all());
        if($_ENV['APP_MODE'] == 'Content' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="content"))
        {
            \Event::fire('backoffice.updated', array('note', $note->id, \Auth::user()->id, $note, 'note updated'));
        }
        return $this->format($note);
    }

    public function delete()
    {
        if($_ENV['APP_MODE'] == 'Content' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="content"))
        {
            $note = $this->repository->find(\Input::get('id'));
            \Event::fire('backoffice.updated', array('note', \Input::get('id'), \Auth::user()->id, $note, 'note deleted'));
        }
        return $this->format($this->repository->destroy(\Input::get('id')));
    }

}

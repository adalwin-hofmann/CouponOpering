<?php
namespace SOE\Api;

class Advertisement extends Api implements ApiInterface, AdvertisementApi
{
    public function __construct(
        \AdvertisementRepositoryInterface $repository,
        \UserRepositoryInterface $users
    )
    {
        $this->personFactory = new \SOE\Persons\PersonFactory;
        $this->repository = $repository;
        $this->users = $users;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
    }

    public function find()
    {
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function search()
    {
        $user = \Auth::check() ? \Auth::User() : false;
        $show_demo = $user ? $this->users->showDemo($user) : false;
        return $this->format(
            $this->repository->search(
                \Input::get('type'), 
                \Input::get('level'), 
                \Input::get('section'), 
                \Input::get('adable_type', null),
                \Input::get('category_id', null), 
                \Input::get('subcategory_id', null), 
                $show_demo, 
                \Input::get('page', 0), 
                \Input::get('limit', 1))
            );
    }

    public function view()
    {
        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$viewable || !$person)
            return false;

        return $this->format($viewable->view($person));
    }
}

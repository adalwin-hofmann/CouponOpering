<?php
namespace SOE\Api;

class BannerEntity extends Api implements ApiInterface, BannerEntityApi
{
    public function __construct(
        \BannerEntityRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
        $this->personFactory = new \SOE\Persons\PersonFactory;
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

    public function delete()
    {
    }

    public function update()
    {
    }

    public function trackImpression()
    {
        $person = $this->personFactory->make();
        $viewable = $this->viewableFactory->make('BannerEntity', \Input::get('banner_entity_id'));
        if($person && $viewable)
            $viewable->view($person);
    }

    public function getByTypeAndLocation()
    {
        return $this->format($this->repository->getByTypeAndLocation(
            \Input::get('type'),
            \Input::get('latitude'),
            \Input::get('longitude'),
            \Input::get('subcategory', null),
            \Input::get('keywords', null),
            \Input::get('limit', 1)
        ));
    }
}

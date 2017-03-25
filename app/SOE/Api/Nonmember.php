<?php
namespace SOE\Api;

class Nonmember extends Api implements ApiInterface, NonmemberApi
{
    public function __construct(
        \NonmemberRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
        $this->printableFactory = new \SOE\Printables\PrintableFactory;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->favoritableFactory = new \SOE\Favoritables\FavoritableFactory;
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

    /**
     * Get an array of recommended Entities based on nonmember_id.
     *
     * @api
     *
     * @return mixed
     */
    public function getRecommendations()
    {
        $lat = \Input::get('latitude', '');
        $lng = \Input::get('longitude', '');

        if ($lat && $lng)
        {
            $zipcodeRepository = \App::make('ZipcodeRepositoryInterface');
            $zip = $zipcodeRepository->getClosest($lat, $lng);
            $geoip = new \StdClass;
            $geoip->city_name = $zip->city;
            $geoip->region_name = $zip->state;
            $geoip->latitude = $lat;
            $geoip->longitude = $lng;
        }
        else
        {
            $geoip = array();
        }

        $nonmember_id = \Input::get("nonmember_id");
        $limit = \Input::get('limit', 0);
        $ordering = \Input::get('order');
        $type = \Input::get('type', 'soe');
        $nonmember = $this->repository->find($nonmember_id);
        $recommendations = $this->repository->getRecommendations($nonmember, $limit, $geoip, $ordering, $type);
        return $this->format($recommendations);
    }

    public function favorite()
    {
        $nonmember = new \SOE\Persons\NonmemberPerson(\Input::get('nonmember_id'));
        $favoritable = $this->favoritableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$favoritable)
            return false;
        return $this->format($favoritable->favorite($nonmember));
    }

    public function deleteFavorite()
    {
        $nonmember = new \SOE\Persons\NonmemberPerson(\Input::get('nonmember_id'));
        $favoritable = $this->favoritableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$favoritable)
            return false;
        return $this->format($favoritable->unfavorite($nonmember));
    }

    /**
     * View an object for the given nonmember.
     *
     * @return mixed
     */
    public function view()
    {
        $nonmember = new \SOE\Persons\NonmemberPerson(\Input::get('nonmember_id'));
        $viewable = $this->viewableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$viewable)
            return false;
        return $this->format($viewable->view($nonmember));
    }

    /**
     * Print an object for the given nonmember.
     *
     * @return mixed
     */
    public function printItem()
    {
        $nonmember = new \SOE\Persons\NonmemberPerson(\Input::get('nonmember_id'));
        $printable = $this->printableFactory->make(\Input::get('type'), \Input::get('object_id'), \Input::get('params'));
        if(!$printable)
            return false;
        return $this->format($printable->printItem($nonmember));
    }
}

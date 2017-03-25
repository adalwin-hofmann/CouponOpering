<?php
namespace SOE\Api;

class Franchise extends Api implements ApiInterface, FranchiseApi
{
    public function __construct(
        \FranchiseRepositoryInterface $repository
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
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function getNotes()
    {
        return $this->format($this->repository->getNotes(\Input::get('franchise_id')));
    }

    public function getByNameWithLeads()
    {
        return $this->format(
            $this->repository->query()
                            ->getByName(\Input::get('name'))
                            ->getsLeads()
                            ->active()
                            ->get(array('franchises.*', \DB::raw('merchants.display as merchant_display')))
        );
    }

    public function getFeaturedDealer()
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        return $this->format(
            $this->repository->getFeaturedDealer($geoip->latitude, $geoip->longitude, \Input::get('dealers', false))
        );
    }

    public function getFeaturedDealers()
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        return $this->format(
            $this->repository->getFeaturedDealers($geoip->latitude, $geoip->longitude, \Input::get('page', 0), \Input::get('limit', 0))
        );
    }

    public function searchTypeahead()
    {
        $query = \Input::get('q');
        return $this->format(
            $this->repository->searchTypeahead($query)
        );
    }
}

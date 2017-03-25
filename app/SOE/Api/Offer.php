<?php
namespace SOE\Api;

class Offer extends Api implements ApiInterface, OfferApi
{
    public function __construct(
        \OfferRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->format($this->repository->with('merchant')
                                            ->where('id', \Input::get('id'))
                                            ->first());
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

    public function getByQuery()
    {
        return $this->format($this->repository->getByQuery(\Input::get('query'), \Input::get('page', 0), \Input::get('limit', 0)));
    }

    public function leaseQuote()
    {
        return $this->format($this->repository->leaseQuote(
            \Input::get('leaseQuoteVehicle'), 
            \Input::get('leaseQuoteFirst'),
            \Input::get('leaseQuoteLast'),
            \Input::get('leaseQuoteEmail'),
            \Input::get('leaseQuotePhone'),
            \Input::get('leaseQuoteZipcode')
        ));
    }

    public function statReport()
    {
        $range = \Input::get('date-range');
        switch ($range) {
            case 'last-30-days':
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
            case 'last-90-days':
                $start = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 90 Days';
                break;
            case 'this-year':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Year';
                break;
            case 'last-year':
                $start = date('Y-01-01 00:00:00', strtotime('-1 year'));
                $end = date('Y-12-31 23:59:59', strtotime('-1 year'));
                $dateText = 'Last Year';
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
        }
        $franchise_id = \Input::get('franchise_id');
        $location_id = \Input::get('location_id', 0);
        return $this->format($this->repository->statReport($franchise_id, $location_id, $start, $end));
    }

}

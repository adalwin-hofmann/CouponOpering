<?php

use SOE\Syndicators\SyndicatorFactory;
use SOE\Syndicators\SyndicatorException;

class SyndicationController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->factory = new SyndicatorFactory;
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
        parent::__construct();
    }

    public function getIndex()
    {                                                   
        try
        {
            $syndicator = $this->factory->build(Input::get('type'));
            return $syndicator->getSyndicatedObjects(Input::get('format', 'rss'), Input::all());
        }
        catch(SyndicatorException $e)
        {
            return Response::json([
                "error" => "an error occurred",
                "details" => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
<?php

class ContentLoaderController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->geoip = json_decode(GeoIp::getGeoIp('json'));
    }

    public function getTermsContent()
    {
        return View::make('master.modals.terms');
    }

    public function getPrivacyContent()
    {
        return View::make('master.modals.privacy');
    }

    public function getDeleteAccountContent()
    {
        return View::make('master.modals.delete-account');
    }

    public function getDeleteAccountConfirmationContent()
    {
        $vw = View::make('master.modals.delete-account-confirmation');
        $vw->geoip = $this->geoip;
        
        return $vw;
    }

    public function getChangeLocationContent()
    {
        $vw = View::make('master.modals.change-location');
        $vw->geoip = $this->geoip;
        
        return $vw;
    }

    public function getShareContent()
    {
        return View::make('master.modals.share');
    }

    public function getAccurateInfoContent()
    {
        return View::make('master.modals.accurate-info');
    }
}
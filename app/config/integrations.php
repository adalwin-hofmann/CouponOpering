<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | The URL used to access your application without a trailing slash. The URL
    | does not have to be set. If it isn't, we'll try our best to guess the URL
    | of your application.
    |
    */


    'iplocation' => array("file"=>"[path]/storage/database/IP-COUNTRY-REGION-CITY-LATITUDE-LONGITUDE.BIN"),
    'customerio' => array('base_url' => 'https://app.customer.io/api/v1/customers/', 'siteid' => '8bd8dad92cf5e9be99e4', 'key' => 'f347b788e6f011e33931'),
    'cloud_search' => array('locations' => array('base_url' => 'http://search-merchant-05-6zmbb3rmifnxbcox5lmvoz6kvy.us-east-1.cloudsearch.amazonaws.com/2011-02-01/search',
                                                'update_url' => 'http://doc-merchant-05-6zmbb3rmifnxbcox5lmvoz6kvy.us-east-1.cloudsearch.amazonaws.com/2011-02-01/documents/batch',
                                                'pagesize'=>'500'),
                            'entities' => array('base_url' => 'http://search-entity-003-dmmtot5tgo72vtrfrwoqx4h3wi.us-east-1.cloudsearch.amazonaws.com/2011-02-01/search',
                                                'update_url' => 'http://doc-entity-003-dmmtot5tgo72vtrfrwoqx4h3wi.us-east-1.cloudsearch.amazonaws.com/2011-02-01/documents/batch',
                                                'pagesize'=>'500')),
    'mixpanel' => array('key' => 'a1491525b75ad031783ea326fcfab48d', 'secret' => '5ea5c2f9ea6bda625cf0914f00971462', 'token' => 'a4eea8975e195cf495f44c2b8ba81147'),
    'facebook' => array('app_id' => '181214118703796', 'secret' => '7deb5e1ad60c302552b16b2fca80b2f1'),
    'utility' => array("base_url" => "http://54.227.254.234/index.php"),
    'google_analytics' => array('pk_location' => storage_path().'/0e4dd5e2d1b949dcd08c992ffa6bf4c791aef443-privatekey.p12', 'profile_id' => 'ga:82279239', 'client_id' => '472301685541.apps.googleusercontent.com', 'service_email' => '472301685541@developer.gserviceaccount.com', 'siteid' => 'UA-48121130-1'),
    'netlms' => array('base_url' => 'http://172.16.2.101', 'key' => '1q2w3e', 'provider_id' => '1', 'provider_key' => '1q2w3e'),
    'infusion_soft' => array('application_name' => 'np174', 'api_key' => '92605d49aee0d26cb53b006395185e90'),
    'detroit_trading' => array('user' => '0000-3084', 'password' => '3084GWQ', 'ftp' => 'ftp.detroittrading.net'),
    'dealer_specialties' => array('ftp' => 'feeds.vehicledata.com', 'user' => 'SaveOnEverything', 'password' => '7de7083a-4'),
    'vauto' => array('ftp' => '54.227.254.234', 'user' => 'vauto', 'password' => 'ce73c925fb7d7c77'),
);
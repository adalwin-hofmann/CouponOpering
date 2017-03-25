<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*Route::get('/', function()
{
    //return View::make('hello');
    print_r(User::all());
});*/

Route::get('location-cookie-clear', function()
{
    $cookie = Cookie::forget('current_location');
    return Response::make('cookie has bee deleted')->withCookie($cookie);
});

Route::any('netlms-test', function()
{
    return View::make('soct.netlms-test');
});

Route::any('new-netlms-test', function()
{
    return View::make('soct.new-netlms-test');
});

Route::any('dt-lead-form', function()
{
    if(Auth::check() && stristr(Auth::User()->type, 'Admin'))
    {
        $vw = View::make('soct.dt-lead-form');
        $franchises = \SOE\DB\Franchise::join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->where('franchises.netlms_id', '!=', '')
            ->where('merchants.vendor', 'soct')
            ->where('merchants.is_active', '1')
            ->orderBy('merchants.name')
            ->get(array('franchises.netlms_id', 'merchants.display'));
        $vw->franchises = $franchises;
        return $vw;
    }
    return Redirect::to('/');
});

Route::post('post-dt-lead', function()
{
    if(Auth::check() && stristr(Auth::User()->type, 'Admin'))
    {
        $adf = '<adf>
        <prospect>
            <requestdate>'.date('H:i:s').'T'.date('H:i:s').'2013-06-12T10:48:21</requestdate>
            <vehicle interest="buy" status="new">
                <year>'.Input::get('year').'</year>
                <make>'.Input::get('make').'</make>
                <model>'.Input::get('model').'</model>
                <trim>Base</trim>
                <colorcombination>
                    <interiorcolor />
                    <exteriorcolor />
                    <preference>1</preference>
                </colorcombination>
                <finance>
                    <balance type="finance" currency="USD" />
                    <amount type="monthly" limit="maximum" currency="USD" />
                    <method>finance</method>
                </finance>
                <odometer status="unknown" units="mi" />
            </vehicle>
            <customer>
                <contact>
                    <name part="first" type="individual">'.Input::get('first_name').'</name>
                    <name part="last" type="individual">'.Input::get('last_name').'</name>
                    <email preferredcontact="0">'.Input::get('email').'</email>
                    <phone type="phone" time="evening" preferredcontact="1">'.Input::get('phone').'</phone>
                    <address type="home">
                        <street line="1"></street>
                        <apartment />
                        <city></city>
                        <regioncode></regioncode>
                        <postalcode>'.Input::get('zipcode').'</postalcode>
                        <country>US</country>
                    </address>
                </contact>
                <timeframe>
                    <description>14 days</description>
                    <earliestdate>2013-06-12T10:48:21</earliestdate>
                    <latestdate>2013-06-26T10:48:21</latestdate>
                </timeframe>
            </customer>
            <vendor>
                <contact>
                    <name part="full" type="business">Save on Everything</name>
                </contact>
            </vendor>
            <provider>
                <name part="full" type="business">Detroit Trading Exchange</name>
                <email preferredcontact="0" />
                <id sequence="0" />
                <service />
                <url />
                <contact>
                    <email preferredcontact="0" />
                    <address type="home">
                        <apartment />
                        <city />
                        <regioncode />
                        <postalcode />
                        <country />
                    </address>
                </contact>
            </provider>
        </prospect>
    </adf>';

        $base_url = \Config::get('integrations.netlms.base_url');
        $base_url .= '/api/v2/lead';
        $base_url .= '?provider_id=1&provider_key=1q2w3e&test='.Input::get('test', 0);
        if(Input::get('directed_to') != '')
            $base_url .= '&directed_to='.Input::get('directed_to');
        $session = curl_init();
        curl_setopt($session, CURLOPT_URL, $base_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($session, CURLOPT_VERBOSE, 1);
        curl_setopt($session, CURLOPT_POSTFIELDS, $adf);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));

        $response = curl_exec($session);
        $status = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        print_r($status);
    }

});

App::before(function($request)
{
    $r = $request->root();
    if (App::environment() == 'prod') {
        if (Feature::findByName('coming_soon')->value == 1 && strpos($r, "saveon.com"))
        {
            $code = array();
            $vw = View::make('home.comingsoon')->with('code', implode(' ', $code));
            $vw->title = "Save On";
            $vw->description = "Save On";

            return $vw;
        }
    }

    $tm = Input::get('tm');
    if($tm != '')
    {
        $tracking = SOE\DB\Tracking::where('code','=',$tm)->first();
        if ((!empty($tracking)) && ((Cookie::get('tracking_id') != 'share') || (Cookie::get('tracking_id') != ''))) {
            $shareid = (Input::get('share'))?Input::get('share'):'0';
            $expire=60*24*$tracking->days;
            Cookie::queue('tracking_id', $tracking->id, $expire);
            Cookie::queue('tracking_type', $tracking->type, $expire);
            Cookie::queue('tracking_url', (isset($_SERVER["HTTP_REFERER"]))?$_SERVER["HTTP_REFERER"]:'0', $expire);
            Cookie::queue('tracking_referid', $shareid, $expire);
        }
    }
});

/*
| Bind Implementations
*/

Route::get('/api/v2/{object}/{action}', function ($object, $action) {
    $features = \App::make('FeatureRepositoryInterface');
    $feature = $features->findByName('api_debug', false);
    $feature = $feature ? $feature->value : 0;
    if($feature)
    {
        $object = studly_case($object);
        $action = studly_case($action);
        $object_name = $object . "Api";
        //$repo_name = $object . "RepositoryInterface";

        // TODO: fix to use IoC container and service provider
        $instance = App::make($object_name);

        // manual dependency injection
        //$repo = new $repo_name;
        //$instance = new $object_name($repo);

        $result = $instance->$action();
        print_r($result);
    }
    else
    {
        try {
            $object = studly_case($object);
            $action = studly_case($action);
            $object_name = $object . "Api";
            //$repo_name = $object . "RepositoryInterface";

            // TODO: fix to use IoC container and service provider
            $instance = App::make($object_name);

            // manual dependency injection
            //$repo = new $repo_name;
            //$instance = new $object_name($repo);

            $result = $instance->$action();
            print_r($result);
        } catch (ErrorException $e) {
            Log::error($e);
            exit;
        }
    }
});

Route::any('/api/{object}/{action}', function($object, $action)
{
    try
    {
        $object = studly_case($object);
        $myobject = $object::blank();
        $methods = get_class_methods($myobject);
        //$action = substr($action, 3);
        $action = 'api'.studly_case($action);
        $found = false;
        foreach($methods as $key => $method)
        {
            if($method == $action)
            {
                $found = true;
                break;
            }
        }
        if($found)
        {
            $response = $object::$action();
            print_r($response);
        }

    }
    catch(ErrorException $e)
    {
        echo "Error: ".PHP_EOL;
        print_r($e);
        exit;
    }
});

Route::any('are-we-up', function()
{
    echo "We are up!";
});

/*** PUSH QUEUE RECEIVER ***/
Route::post('queue/receive', function()
{
    return Queue::marshal();
});

/***** CONTENT MODE ROUTES *****/
/*******************************/

if($_ENV['APP_MODE'] == 'Content' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="content"))
{
    Route::get('/onlinetraining/content-page/{one}/{two}/{three?}', 'TrainingController@contentPage');
    Route::controller('/onlinetraining', 'TrainingController');
    Route::controller('/seo', 'SeoController');
    Route::controller('/', 'ContentController');
}

/***** DIGITAL MODE ROUTES *****/
/*****************************/

if($_ENV['APP_MODE'] == 'Digital' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="digital"))
{
    Route::get('/onlinetraining/content-page/{one}/{two}/{three?}', 'TrainingController@contentPage');
    Route::controller('/onlinetraining', 'TrainingController');
    Route::controller('/', 'TrainingController');
}

/***** PRODUCTION MODE ROUTES *****/
/*****************************/

if($_ENV['APP_MODE'] == 'Production' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="production"))
{
    Route::get('/onlinetraining/content-page/{one}/{two}/{three?}', 'TrainingController@contentPage');
    Route::controller('/onlinetraining', 'TrainingController');
    Route::controller('/', 'TrainingController');
}

/***** SALES MODE ROUTES *****/
/*****************************/

if($_ENV['APP_MODE'] == 'Sales' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="sales"))
{
    Route::get('/onlinetraining/content-page/{one}/{two}/{three?}', 'TrainingController@contentPage');
    Route::controller('/onlinetraining', 'TrainingController');
    Route::controller('/', 'SalesController');
}

/***** MERCHANT MODE ROUTES *****/
/*****************************/

if($_ENV['APP_MODE'] == 'Merchant' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="merchant"))
{
    Route::controller('/', 'MerchantController');
}

/***** CONSUMER MODE ROUTES *****/
/********************************/

if($_ENV['APP_MODE'] == 'Consumer' || (isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="consumer"))
{
    Route::any('/scheduled/{command}', 'ScheduledController@dispatcher');

    Route::any('/files-ready', function()
    {
        DB::table('sync')->where('is_imported', '=', '0')->update(array('is_imported' => 1));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'assets'
        ));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'category_assets'
        ));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'merchants'
        ));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'locations'
        ));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'entities'
        ));

        DB::table('sync')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'import_type' => 'banners'
        ));
    });

    Route::any('/filesync', function()
    {
        set_time_limit(60*120); // 2 Hours
        ini_set('memory_limit', '4096M');
        $import = DB::table('sync')->where('is_imported', '=', '0')->orderBy('created_at')->first();
        if(empty($import))
        {
            echo "Nothing to Import";
            return;
        }
        //print_r($import);exit;
        if($import->is_running == 1)
        {
            echo "Import of ".$import->import_type." is running.";
            return;
        }
        else
        {
            DB::table('sync')->where('id', '=', $import->id)->update(array('is_running' => 1));
            Artisan::call('import', ['--type'=> $import->import_type]);
            DB::table('sync')->where('id', '=', $import->id)->update(array('is_running' => 0, 'is_imported' => 1));
        }
    });

    Route::any('/check-soct-feature', function()
    {
        $feature = \SOE\DB\Feature::where('name', 'soct_redirect')->first();
        return $feature ? $feature->value : 0;
    });

    // Redirects from old site
    Route::get('/category/{param1}/{param2?}/{param3?}/{param4?}/{param5?}/{param6?}', 'CouponsController@oldCoupons');
    Route::get('/mobile2/{param1}/{param2}/{param3}/{param4}/{param5?}/{param6?}', 'HomeController@getMobile2');
    Route::get('/merchant/{param1}/{param2}/{param3?}', function($merchantslug, $merchantid, $coupon = 'coupon')
    {
        $merchant = Merchant::find($merchantid);
        if (empty($merchant))
        {
            return Redirect::to('/');
        }
        $category = Category::find($merchant->category_id);
        $subcategory = Category::find($merchant->subcategory_id);
        $locationFilter = array(array('type'=>'where','key'=>'merchant_id','operator'=>'=','value'=>$merchant->id),
            array('type'=>'where','key'=>'is_national','operator'=>'!=','value'=>'1'));
        $allLocations = Location::get($locationFilter, 1, 0);
        $locationCount = $allLocations['stats']['total'];
        if ($locationCount > 1)
        {
            return Redirect::to('/directions/'.$merchant->slug.'/'.$merchant->id, 301);
        } elseif ($locationCount == 1) {
            $location = array_shift($allLocations['objects']);
            $cat = $category ? $category->slug : 'category';
            $subcat = $subcategory ? $subcategory->slug : 'subcategory';
            return Redirect::to('/coupons/'.$cat.'/'.$subcat.'/'.$merchant->slug.'/'.$location->id, 301);
        } else {
            return Redirect::to('/');
        }
    });
    Route::get('/print-coupon/{param1}/{param2}', function($merchantid, $couponid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$couponid)->where('merchant_id','=',$merchantid)->first();
        return Redirect::to('/?showeid='.$entity->id, 301);
    });
    Route::get('/member/save/deal/{param1}', function($couponid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$couponid)->where('entitiable_type','=','Offer')->first();
        return Redirect::to('/?showeid='.$entity->id, 301);
    });
    Route::get('/member/save', function()
    {
        return Redirect::to('/dailydeals/all', 301);
    });
    Route::get('/win/{param1}/{param2}/registration', function($contest_slug, $contestid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$contestid)->where('entitiable_type','=','Contest')->first();
        return Redirect::to('/contests/all?showeid='.$entity->id, 301);
    });
    Route::get('/national', function()
    {
        return Redirect::to('/', 301);
    });
    Route::get('/national/business/{param1}/{param2}', function($merchantslug, $merchantid)
    {
        $merchant = Merchant::find($merchantid);
        return Redirect::to('/directions/'.$merchant->slug.'/'.$merchant->id, 301);
    });
    Route::get('/national/location/{param1}/{param2}/{param3}', function($merchantid, $merchantslug, $locationid)
    {
        $location = Location::find($locationid);
        $merchant = Merchant::find($location->merchant_id);
        return Redirect::to('/coupons/'.$merchant->slug.'/'.$location->id, 301);
    });
    Route::get('/national/print_coupon/{param1}/{param2}/{param3}', function($merchantid, $locationid, $couponid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$couponid)->where('location_id','=',$locationid)->first();
        return Redirect::to('/?showeid='.$entity->id, 301);
    });
    Route::get('/national/save/{param1}/{param2}', function($locationid, $couponid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$couponid)->where('location_id','=',$locationid)->first();
        return Redirect::to('/?showeid='.$entity->id, 301);
    });
    Route::get('/national/detail/{param1}', function($couponid)
    {
        // Need better way to get offer data
        $entity = SOE\DB\Entity::where('entitiable_id','=',$couponid)->where('entitiable_type','=','Offer')->first();
        return Redirect::to('/?showeid='.$entity->id, 301);
    });
    Route::get('/corporate', function()
    {
        return Redirect::to('/heritage', 301);
    });
    Route::get('/corporate/heritage', function()
    {
        return Redirect::to('/heritage', 301);
    });
    Route::get('/corporate/leadership', function()
    {
        return Redirect::to('/heritage', 301);
    });
    Route::get('/corporate/workingat', function()
    {
        return Redirect::to('/heritage', 301);
    });
    Route::get('/corporate/locations', function()
    {
        return Redirect::to('/headquarters', 301);
    });
    Route::get('/corporate/featuredmerchants', function()
    {
        return Redirect::to('/featuredmerchants', 301);
    });
    Route::get('/corporate/products/print', function()
    {
        return Redirect::to('/printproducts', 301);
    });
    Route::get('/corporate/products/digital', function()
    {
        return Redirect::to('/digitalproducts', 301);
    });
    Route::get('/corporate/products/advertise', function()
    {
        return Redirect::to('/whyadvertise', 301);
    });
    Route::get('/corporate/our-brands', function()
    {
        return Redirect::to('/brands', 301);
    });
    Route::get('/corporate/adspecs', function()
    {
        return Redirect::to('/adspecs', 301);
    });
    Route::get('/corporate/faq', function()
    {
        return Redirect::to('/faqs', 301);
    });
    Route::get('/corporate/maps', function()
    {
        return Redirect::to('/maps', 301);
    });
    Route::get('/corporate/fileupload', function()
    {
        return Redirect::to('/fileupload', 301);
    });
    Route::get('/corporate/news', function()
    {
        return Redirect::to('/news', 301);
    });
    Route::get('/corporate/contact', function()
    {
        return Redirect::to('/contact', 301);
    });
    Route::get('/corporate/careers', function()
    {
        return Redirect::to('/careers', 301);
    });
    Route::get('/member', function()
    {
        return Redirect::to('/members/dashboard', 301);
    });
    Route::get('/member/settings', function()
    {
        return Redirect::to('/members/mysettings', 301);
    });
    Route::get('/member/organizer', function()
    {
        return Redirect::to('/members/mycoupons', 301);
    });
    Route::get('/member/personalize', function()
    {
        return Redirect::to('/members/myinterests', 301);
    });
    Route::get('/member/win', function()
    {
        return Redirect::to('/contests/all', 301);
    });
    Route::get('/member/contests', function()
    {
        return Redirect::to('/contests/all', 301);
    });
    Route::get('/member/subscriptions', function()
    {
        return Redirect::to('/members/mynotifications', 301);
    });
    Route::get('/cities', function()
    {
        return Redirect::to('/country', 301);
    });
    Route::get('/category/food_dining', function()
    {
        return Redirect::to('/coupons/food-dining', 301);
    });
    Route::get('/city/{param1}', function($city=null)
    {
        $city_images = SOE\DB\CityImage::where('name','=',$city)->first();
        if (empty($city_images))
        {
            $cities = SOE\DB\Zipcode::where('zipcode','=',$city)->first();
        } else {
            $cities = SOE\DB\Zipcode::where('latitude','=',$city_images->latitude)->where('longitude','=',$city_images->longitude)->first();
        }
        if (empty($cities))
        {
            return Redirect::to('/');
        }
        return Redirect::to('/coupons/in/'.SoeHelper::getSlug(strtolower($cities->city)).'/'.strtolower($cities->state), 301);
    });
    Route::get('/{win5k}', function()
    {
        // Need better way to get offer data
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $entity = SOE\DB\Entity::where('name','=','win5k')->where('state','=',$geoip->region_name)->where('is_active', '=', '1')->first();
        if (empty($entity))
        {
            return Redirect::to('/');
        }
        return Redirect::to('/contests/all?showeid='.$entity->id, 301);
    })->where('win5k', '(?i)win5k(?-i)');
    //triggers sign up modal via url /signup
    Route::get('/signup', function()
    {
        return Redirect::to('/?modal=signUpModal', 301);
    });
    //triggers sign in modal via url /signin
    Route::get('/signin', function()
    {
        return Redirect::to('/?modal=signInModal', 301);
    });
    //triggers sign in modal via url /login
    Route::get('/login', function()
    {
        return Redirect::to('/?modal=signInModal', 301);
    });

    Route::get('/locations', function()
    {
        return Redirect::to('/', 301);
    });

    Route::controller('/commands/{command?}/{type?}', 'CommandsController');
    Route::get('/HomeImprovement', function(){return Redirect::to('/homeimprovement', 301);});
    Route::get('/coupons/franchise-rss/{location_id}', 'CouponsController@franchiseRss');
	Route::get('/coupons/coupons-rss/{type}', 'CouponsController@couponsRss');
	Route::get('/dealers-rss', 'CouponsController@carDealersRss');
    Route::controller('/schools/{state?}/{city?}/{category?}/{subcategory?}/{merchant?}/{location?}', 'CouponsController');
    Route::controller('/coupons/{state?}/{city?}/{category?}/{subcategory?}/{merchant?}/{location?}', 'CouponsController');
    Route::any('/dailydeals/{state?}/{city?}/{category?}/{subcategory?}/{merchant?}/{location?}', 'CouponsController@dailydeals');
    Route::any('/contests/{state?}/{city?}/{category?}/{subcategory?}/{merchant?}/{location?}', 'CouponsController@contests');
    Route::controller('/directions/{slug}/{state?}/{city?}/{id?}', 'DirectionsController');
    Route::controller('/members', 'MembersController');
    Route::controller('/email', 'EmailController');

    Route::get('/homeimprovement/coupons/{cat}/brick-paver', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/brick-paver-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/chimney-fireplace', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/chimney-fireplace-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/concrete-brick-masonry', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/concrete-brick-masonry-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/decks', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/decks-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/garage-door', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/garage-door-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/heating-cooling', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/heating-cooling-new', 301);
    });
    Route::get('/homeimprovement/coupons/{cat}/pools-hot-tubs', function($cat)
    {
        return Redirect::to('homeimprovement/coupons/'.$cat.'/pools-hot-tubs-new', 301);
    });

    Route::controller('/homeimprovement', 'SohiController');
    Route::get('/cars/new/netlms.js', 'CarsController@newNetlmsJs');
    Route::get('/cars/new/rss', 'CarsController@newRss');
    Route::any('/cars/new/{state?}/{city?}/{year?}/{make?}/{model?}/{dealer?}', 'CarsController@newCars');
    Route::get('/cars/used/rss', 'CarsController@usedRss');
    Route::any('/cars/used/under/{price?}/{make?}/{state?}/{city?}/{style?}', 'CarsController@usedUnder');
    Route::any('/cars/used/{state}/{city}/{year}/{make}/{model?}/{dealer?}', 'CarsController@oldRedirects')
        ->where(array(
            'year' => '[0-9]+|all',
            'make' => '[a-zA-Z-]+'
        ));
    Route::any('/cars/used/{state?}/{city?}/{make?}/{price?}/{style?}', 'CarsController@usedCars');
    //Route::any('/cars/used/{state?}/{city?}/{year?}/{make?}/{model?}/{dealer?}', 'CarsController@usedCars');
    Route::post('/cars/vehicle-search', 'CarsController@vehicleSearch');
    Route::any('/cars/research/{year?}/{make?}/{model?}/{style?}', 'CarsController@research');
    Route::any('/cars/auto-services/{state?}/{city?}', 'CarsController@autoServices');
    Route::any('/cars/featured-dealers/{state?}/{city?}', 'CarsController@featuredDealers');
    Route::get('/cars/netlms.js', 'CarsController@netlmsJs');
    Route::post('/cars/affiliate-quote', 'CarsController@affiliateQuote');
    Route::post('/cars/affiliate-new-quote', 'CarsController@affiliateNewQuote');
    Route::post('/cars/affiliate-view', 'CarsController@affiliateView');
    Route::controller('/cars', 'CarsController');
    Route::controller('/reports', 'ReportsController');
    Route::controller('/content-loader', 'ContentLoaderController');
    Route::controller('/whitelabel', 'WhitelabelController');
    $travelFeature = \SOE\DB\Feature::where('name', 'saveon_travel')->first();
    if(($travelFeature) && ($travelFeature->value == 1)) {
        Route::controller('/travel/cruises', 'CruisesController');
        Route::controller('/travel', 'TravelController');
    }
    Route::controller('/syndication', 'SyndicationController');
    Route::controller('/', 'HomeController');

    // Not from old site. Mediakit was renamed to presskit.
    Route::get('/mediakit', function()
    {
        return Redirect::to('/presskit', 301);
    });
}

App::missing(function($exception)
{
    $url = str_replace('/','',Request::path());
    $company = SOE\DB\Company::where('slug', '=', $url)->first(); // Check White Label
    if(!empty($company))
    {
        $code = array();
        $code[] = View::make('home.jscode.masonry');
        $code[] = View::make('home.jscode.index');
        $code[] = View::make('home.jscode.tour');
        $vw = View::make('home.index')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";

        //$geoip = json_decode(GeoIp::getGeoIp('json'));
        $cartesian = SoeHelper::getCartesian($company->latitude, $company->longitude);
        $city_image = SOE\DB\CityImage::where('region_type', '=', 'City')
            ->orderBy('distance', 'asc')
            ->remember(600)
            ->first(array(
                'city_images.*',
                DB::raw('(sqrt(pow(city_images.latm - '.$cartesian['latm'].', 2) + pow(city_images.lngm - '.$cartesian['lngm'].', 2))) as distance')));
        $vw->city_image = $city_image;
        $vw->company = $company;
        $vw->sohi = 0;

        $zipcodes = App::make('ZipcodeRepositoryInterface');
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $users = App::make('UserRepositoryInterface');
        $nonmembers = App::make('NonmemberRepositoryInterface');

        // Popup White Label Modal?
        if (parse_url(URL::previous(), PHP_URL_HOST) == 'www.thewaveofsavings.com')
        {
            if(!Session::has('fireFirstTimeCompany'))
            {
                Session::put('fireFirstTimeCompany', 1);
                $vw->fireFirstTimeCompanyModal = 1;
            }
        }

        // Load recommendations
        $lat = $geoip->latitude;
        $lng = $geoip->longitude;
        $zip = $zipcodes->getClosest($lat, $lng);
        $geoip = new \StdClass;
        $geoip->city_name = $zip->city;
        $geoip->region_name = $zip->state;
        $geoip->latitude = $lat;
        $geoip->longitude = $lng;
        if(Auth::check())
        {
            $user = Auth::User();
            $recommendations = $users->getRecommendations($user, 12, $geoip, 'score');
        }
        else
        {
            $nonmember = Auth::nonmember();
            $recommendations = $nonmembers->getRecommendations($nonmember, 12, $geoip, 'score');
        }
        $vw->entities = $recommendations['objects'];

        return $vw;
    } else {
        return Response::view('errors.missing', array(), 404);
    }
});

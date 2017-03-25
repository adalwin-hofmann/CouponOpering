<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/database/seeds',
    app_path().'/SOE',
    app_path().'/SOE/Extensions',
    app_path().'/SOE/Handlers',
    app_path().'/SOE/Helpers',
    app_path().'/SOE/Services',
    app_path().'/SOE/Repositories',
    app_path().'/SOE/Queues',
));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
    $geoip = json_decode(GeoIp::getGeoIp('json'));
    $data = array(
        'exception' => $exception,
        'referrer' => URL::previous(),
        'current' => URL::current(),
        'city' => $geoip->city_name,
        'state' => $geoip->region_name,
        'latitude' => $geoip->latitude,
        'longitude' => $geoip->longitude,
        'inputs' => Input::all()
    );
    $repo = App::make('FeatureRepositoryInterface');
    $error_emails = $repo->findByName('error_emails', false);
    $error_emails = empty($error_emails) ? 0 : $error_emails->value;
    if(App::environment() == 'prod' && $error_emails)
    {
        Mail::send('emails.error', $data, function($message)
        {
            $message->to('mcrandell@saveon.com', 'Matt Crandell')->subject('SaveOn Error!');
            $message->cc('cbeery@saveon.com', 'Caleb Beery');
            $message->cc('abedor@saveon.com', 'Aaron Bedor');
        });
        return Response::view('errors.whoops', array(), 500);
    }
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/**
 * Bootstrap the SOE application
 */
require app_path().'/SOE/start.php';

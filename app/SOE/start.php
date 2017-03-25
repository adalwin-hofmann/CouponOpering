<?php namespace SOE;

/**
 * Register a method to return a JSON response when an exception occurs
 *
 * Response::error takes an \Exception and an optional base level error message. The exception's
 * error code will be used as the response code, and the message will be sent as the error details
 */
\Response::macro('error', function(\Exception $e, $message = '') {
    return \Response::json([
        "error" => $message ?: "an error occurred",
        "details" => $e->getMessage()
        ], $e->getCode() ?: 400);
});

/**
 * Register a custom Auth driver "replication".
 *
 * The replication Auth driver makes use of the custom EloquentUserProvider which ensures that
 * all reads / writes done by the Auth class use the "mysql-write" database connection to prevent
 * issues caused by replication lag.
 */
\Auth::extend('replication', function($app)
{
    $model = $app['config']['auth.model'];
    return new \SOE\Extensions\EloquentUserProvider($app['hash'], $model);
});
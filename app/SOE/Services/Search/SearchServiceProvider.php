<?php namespace SOE\Services\Search;

use Illuminate\Support\ServiceProvider;
use App;
use Illuminate\Foundation\AliasLoader;

class SearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        App::bind('search', function()
        {
            return new CloudSearch(\App::make('UserRepositoryInterface'), \App::make('FeatureRepositoryInterface'));
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        App::booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Search', 'SOE\Facades\Search');
        });
    }
}
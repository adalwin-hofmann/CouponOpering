<?php namespace SOE\Services\FileStore;

use Illuminate\Support\ServiceProvider;
use App;

class FileStoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        App::bind('FileStoreInterface', 'SOE\Services\FileStore\S3FileStore');
    }
}
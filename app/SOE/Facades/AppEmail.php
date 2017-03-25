<?php namespace SOE\Facades;

use Illuminate\Support\Facades\Facade;

class AppEmail extends Facade {

    protected static function getFacadeAccessor() { return 'appemail'; }

}
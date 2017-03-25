<?php namespace SOE\Facades;

use Illuminate\Support\Facades\Facade;

class Nonmember extends Facade {

    protected static function getFacadeAccessor() { return 'nonmember'; }

}
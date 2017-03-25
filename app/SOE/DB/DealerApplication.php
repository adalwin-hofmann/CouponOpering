<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class DealerApplication extends Eloquent 
{
    protected $table = 'dealer_applications';

    public static function boot()
    {
        parent::boot();

        DealerApplication::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    public function setAccountPasswordAttribute($password)
    {
        $this->attributes['account_password'] = \Hash::make($password);
    }
}
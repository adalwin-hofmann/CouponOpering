<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContractorApplication extends Eloquent 
{
    protected $table = 'contractor_applications';

    public static function boot()
    {
        parent::boot();

        ContractorApplication::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    public function setAccountPasswordAttribute($password)
    {
        $this->attributes['account_password'] = \Hash::make($password);
    }
}
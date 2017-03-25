<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContractorApplicationTag extends Eloquent 
{
    protected $table = 'contractor_application_tags';

    public static function boot()
    {
        parent::boot();

        ContractorApplicationTag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
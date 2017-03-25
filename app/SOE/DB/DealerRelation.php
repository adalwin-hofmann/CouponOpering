<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class DealerRelation extends Eloquent 
{
    protected $table = 'dealer_relations';

    public static function boot()
    {
        parent::boot();

        DealerRelation::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
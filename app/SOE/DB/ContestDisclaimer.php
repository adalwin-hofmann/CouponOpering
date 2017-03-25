<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContestDisclaimer extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ContestDisclaimer::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "contest_disclaimers".  */
    protected $table = 'contest_disclaimers';

}
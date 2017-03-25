<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class TrainingPage extends Eloquent 
{
    /** Force database to use table "training_pages".  */
    protected $table = 'training_pages';

    public static function boot()
    {
        parent::boot();

        District::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
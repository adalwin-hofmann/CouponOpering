<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class TrainingSection extends Eloquent 
{
    /** Force database to use table "training_pages".  */
    protected $table = 'training_sections';

    public static function boot()
    {
        parent::boot();

        District::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
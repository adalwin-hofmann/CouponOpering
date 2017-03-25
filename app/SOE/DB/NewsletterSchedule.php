<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class NewsletterSchedule extends Eloquent 
{
    /** Force database to use table "newsletter_schedules".  */
    protected $table = 'newsletter_schedules';

    public static function boot()
    {
        parent::boot();

        NewsletterSchedule::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ActionLog extends Eloquent 
{
    /** Force database to use table "action_logs".  */
    protected $table = 'action_logs';

    public static function boot()
    {
        parent::boot();

        ActionLog::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
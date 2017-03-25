<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class SysLog extends Eloquent 
{
    protected $table = 'sys_logs';

    public static function boot()
    {
        parent::boot();

        SysLog::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class IPCache extends Eloquent 
{
	/** Force database to use table "ipcache".  */
    protected $table = 'ipcache';

    protected $fillable = array('ipaddress');

    public static function boot()
    {
        parent::boot();

        IPCache::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     
}
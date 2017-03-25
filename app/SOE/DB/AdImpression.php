<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class AdImpression extends Eloquent 
{
    /** Force database to use table "ad_impressions".  */
    protected $table = 'ad_impressions';

    public static function boot()
    {
        parent::boot();

        AdImpression::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     

    public function advertisement()
    {
        return $this->belongsTo('\SOE\DB\Advertisement');
    }
}
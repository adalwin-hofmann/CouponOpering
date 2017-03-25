<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class UserLinkClick extends Eloquent 
{
    /** Force database to use table "user_link_clicks".  */
    protected $table = 'user_link_clicks';

    public static function boot()
    {
        parent::boot();

        UserLinkClick::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
}

<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class UserSearch extends Eloquent 
{
    /** Force database to use table "user_searches".  */
    protected $table = 'user_searches';

    public static function boot()
    {
        parent::boot();

        UserSearch::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * UserSearch can have on User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}
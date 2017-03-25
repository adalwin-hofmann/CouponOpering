<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class UserView extends Eloquent 
{
    /** Force database to use table "user_views".  */
    protected $table = 'user_views';

    public static function boot()
    {
        parent::boot();

        UserView::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * UserView can have on User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}

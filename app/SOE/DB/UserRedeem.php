<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class UserRedeem extends Eloquent 
{
    /** Force database to use table "user_redeems".  */
    protected $table = 'user_redeems';

    public static function boot()
    {
        parent::boot();

        UserRedeem::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * UserRedeem can have on User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}
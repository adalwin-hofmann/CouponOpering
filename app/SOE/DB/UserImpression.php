<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class UserImpression extends Eloquent 
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_impressions';

    public static function boot()
    {
        parent::boot();

        UserImpression::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    
    /**
     * UserImpression can have on User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}
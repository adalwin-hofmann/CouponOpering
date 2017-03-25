<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Share extends Eloquent 
{

    public static function boot()
    {
        parent::boot();

        Share::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  

    /**
     * User_View can have on User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}
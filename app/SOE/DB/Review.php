<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Review extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Review::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
    
	/**
     * Review can have one User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}
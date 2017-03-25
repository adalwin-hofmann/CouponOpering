<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

/**
* UserPrint.
*
* A print by a User.
*
* @author Caleb Beery <cbeery@saveoneverything.com>
*
*/

class UserPrint extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_prints';

    public static function boot()
    {
        parent::boot();

        UserPrint::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    /**
    * Get the User associated with this UserPrint.
    *
    * @return User
    */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
    * Get the Offer associated with this UserPrint.
    *
    * @return Offer
    */
    public function offer()
    {
        return $this->belongsTo('Offer');
    }
}
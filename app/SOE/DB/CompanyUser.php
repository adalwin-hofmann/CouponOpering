<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class CompanyUser extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        CompanyUser::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

	/**
    * Get the Company associated with this User.
    *
    * @return User
    */
    public function user()
    {
        return $this->belongsTo('Company');
    }
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Rule extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Rule::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

	/**
     * Rule has many Roles
     *
     * @return Role
     */
    public function roles()
    {
        return $this->belongsToMany('Role');
    }
}
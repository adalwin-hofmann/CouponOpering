<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Role extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        Role::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

	/**
     * Role can have many Rules
     *
     * @return Role
     */
    public function rules()
    {
        return $this->belongsToMany('Rule');
    }
}
<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* Tag.
*
* A gallery tag.
*
* @author Caleb Beery <cbeery@saveoneverything.com>
*
*/

class Tag extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tags';

    public static function boot()
    {
        parent::boot();

        Tag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    
}
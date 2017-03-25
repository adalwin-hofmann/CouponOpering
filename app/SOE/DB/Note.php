<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Note extends Eloquent 
{
    /** Force database to use table "notes".  */
    protected $table = 'notes';

    public static function boot()
    {
        parent::boot();

        Note::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    public function notable()
    {
        return $this->morphTo();
    } 
}
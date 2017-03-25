<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContestLocation extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ContestLocation::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "contest_locations".  */
    protected $table = 'contest_locations';
    public $timestamps = false;

    /**
     * Each ContestLocation belongs to a single Contest
     *
     * @return Contest
     */
    public function contest()
    {
        return $this->belongsTo('Contest');
    }
}
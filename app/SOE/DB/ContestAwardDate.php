<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContestAwardDate extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ContestAwardDate::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "contest_award_dates".  */
    protected $table = 'contest_award_dates';

    /**
     * Each ContestAwardDate belongs to a single Contest
     *
     * @return Contest
     */
    public function contest()
    {
        return $this->belongsTo('Contest');
    }

}